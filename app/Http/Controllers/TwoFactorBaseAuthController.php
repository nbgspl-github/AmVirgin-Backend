<?php

namespace App\Http\Controllers;

use App\Exceptions\OtpMismatchException;
use App\Exceptions\OtpNotFoundException;
use App\Exceptions\OtpPushException;
use App\Exceptions\ResourceConflictException;
use App\Exceptions\ValidationException;
use App\Models\Settings;
use App\Traits\FluentResponse;
use App\Traits\GuestOtpVerificationSupport;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

/**
 * Class TwoFactorBaseAuthController
 * @package App\Http\Controllers
 */
abstract class TwoFactorBaseAuthController extends BaseAuthController
{
	use FluentResponse;
	use GuestOtpVerificationSupport;

	const Type = [
		'Email' => 1,
		'Mobile' => 2,
		'2Factor' => 3,
	];

	protected abstract function otpTarget (): string;

	protected function shouldVerifyOtpBeforeRegister (): bool
	{
		return true;
	}

	protected function shouldVerifyOtpBeforeLogin (): bool
	{
		return true;
	}

	protected function exists ()
	{
		$request = request();
		$type = $request->type;
		$response = $this->responseApp();
		$payload = null;
		try {
			$payload = $this->requestValid($request, $this->rulesExists());
			$conditions = $this->conditionsExists(request());
			$user = $this->throwIfNotFound($conditions);
			if ($type == self::Type['2Factor']) {
				$otp = $user->sendVerificationOtp();
				$user->setOtp($otp)->save();
				$response->status(HttpResourceAlreadyExists)->message('Otp has been sent to your registered mobile number.');
			} else {
				$response->status(HttpResourceAlreadyExists)->message('User found for that key.');
			}
		} catch (OtpPushException $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} catch (ModelNotFoundException $exception) {
			if ($type == self::Type['2Factor']) {
				try {
					$otp = $this->sendGuestOtp($payload['mobile']);
					$this->otpTarget()::updateOrCreate(
						[
							'mobile' => $payload['mobile'],
						],
						[
							'otp' => $otp,
						]
					);
					$response->status(HttpResourceNotFound)->message('Not found');
				} catch (OtpPushException $exception) {
					$response->status(HttpServerError)->message($exception->getMessage());
				}
			} else {
				$response->status(HttpResourceNotFound)->message('User not found for that key.');
			}
		} catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function login ()
	{
		$request = request();
		$type = $request->type;
		$response = $this->responseApp();
		$payload = null;
		try {
			$payload = $this->requestValid($request, $this->rulesLogin());
			$conditions = $this->conditionsExists(request());
			$user = $this->throwIfNotFound($conditions);
			if ($type == self::Type['2Factor']) {
				if ($this->shouldVerifyOtpBeforeLogin()) {
					throw_if((is_null($userOtp = $user->otp)), new OtpNotFoundException());
					throw_if(($payload['otp'] != $userOtp), new OtpMismatchException());
				}
				$token = $this->generateToken($user);
				$response->message($this->loginSuccess())->setValue('data', $this->loginPayload($user, $token))->status(HttpOkay);
			} else {
				if ($this->shouldAllowOnlyActiveUsers() && !$user->isActive()) {
					$response->status(HttpDeniedAccess)->message($this->deniedAccess());
				}
				$token = $this->guard()->attempt($this->credentials($request));
				if (!$token)
					$response->message($this->loginFailed())->status(HttpUnauthorized);
				else
					$response->message($this->loginSuccess())->setValue('data', $this->loginPayload($user, $token));
			}
		} catch (OtpMismatchException $exception) {
			$response->status(HttpUnauthorized)->message($exception->getMessage());
		} catch (OtpPushException $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message(__('strings.customer.not-found'));
		} catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function register ()
	{
		$request = request();
		$type = $request->type;
		$response = $this->responseApp();
		$payload = null;
		try {
			$payload = $this->requestValid($request, $this->rulesRegister());
			$conditions = $this->conditionsExists(request());
			$this->throwIfFound($conditions);
			if (!$this->shouldVerifyOtpBeforeRegister()) {
				throw_if((is_null($userOtp = $this->otpTarget()::retrieve($payload['mobile']))), new OtpNotFoundException());
				throw_if(($payload['otp'] != $userOtp->getOtp()), new OtpMismatchException());
			}
			$user = $this->create($request);
			$token = $this->generateToken($user);
			if (!$token)
				$response->message($this->registerFailed())->status(HttpServerError);
			else
				$response->message($this->registerSuccess())->status(HttpCreated)->setValue('data', $this->registerPayload($user, $token));
		} catch (OtpNotFoundException $exception) {
			$response->status(HttpDeniedAccess)->message($exception->getMessage());
		} catch (OtpMismatchException $exception) {
			$response->status(HttpUnauthorized)->message($exception->getMessage());
		} catch (OtpPushException $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} catch (ResourceConflictException $exception) {
			$response->status(HttpResourceAlreadyExists)->message($this->registerTaken());
		} catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected abstract function rulesSocialLogin (): array;

	protected function socialLogin ()
	{
		$response = $this->responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rulesSocialLogin());
			$user = $this->authTarget()::where('email', $validated['email'])->first();
			if ($user == null) {
				$validated['password'] = Hash::make('12345678');
				$user = $this->authTarget()::create($validated);
			}
			$token = $this->generateToken($user);
			$response->message($this->loginSuccess())->setValue('data', $this->registerPayload($user, $token))->status(HttpOkay);
		} catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function handleGoogleCallback ()
	{
		try {
			$user = Socialite::driver('google')->user();
			$exists = User::where('email', $user->getEmail())->first();
			if ($exists) {
				auth()->login($exists);
				return redirect()->route('home');
			} else {
				$contents = file_get_contents($user->user['picture']);
				$avatarUrl = 'avatars' . '/' . $user->id . '.jpg';
				Storage::disk('public')->put($avatarUrl, $contents);
				$newUser = User::create([
					'first_name' => $user->user['given_name'],
					'last_name' => $user->user['family_name'],
					'email' => $user->email,
					'google_id' => $user->id,
					'avatar' => $avatarUrl,
					'color' => Settings::color(),
					'font' => Settings::font()
				]);
				auth()->login($newUser, true);
				return redirect()->route('home');
			}
		} catch (\Throwable $e) {
			toastError(trans("strings.google_login_failed"));
			return redirect()->route('login');
		}
	}
}