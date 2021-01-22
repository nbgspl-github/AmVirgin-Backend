<?php

namespace App\Http\Modules\Shared\Controllers\Auth;

use App\Exceptions\OtpMismatchException;
use App\Exceptions\OtpNotFoundException;
use App\Exceptions\OtpPushException;
use App\Exceptions\ResourceConflictException;
use App\Exceptions\ValidationException;
use App\Traits\FluentResponse;
use App\Traits\GuestOtpVerificationSupport;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * Class TwoFactorBaseAuthController
 * @package App\Http\Controllers
 */
abstract class TwoFactorBaseAuthController extends BaseAuthController
{
	use FluentResponse;
	use GuestOtpVerificationSupport;

	const EMAIL = 1;
	const MOBILE = 2;
	const OTP = 3;

	const Type = [
		'Email' => 1,
		'Mobile' => 2,
		'2Factor' => 3,
	];

	protected abstract function otpTarget () : string;

	protected function shouldVerifyOtpBeforeRegister () : bool
	{
		return true;
	}

	protected function shouldVerifyOtpBeforeLogin () : bool
	{
		return true;
	}

	protected function exists () : \Illuminate\Http\JsonResponse
	{
		$request = request();
		$type = $request->type;
		$response = responseApp();
		$payload = null;
		try {
			$payload = $this->requestValid($request, $this->rulesExists());
			$conditions = $this->conditionsExists(request());
			$user = $this->throwIfNotFound($conditions);
			if ($type == self::Type['2Factor']) {
				$otp = $user->sendVerificationOtp();
				$user->setOtp($otp)->save();
				$response->status(\Illuminate\Http\Response::HTTP_CONFLICT)->message('Otp has been sent to your registered mobile number.');
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_CONFLICT)->message('User found for that key.');
			}
		} catch (OtpPushException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
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
					$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Not found');
				} catch (OtpPushException $exception) {
					$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
				}
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('User not found for that key.');
			}
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function login () : \Illuminate\Http\JsonResponse
	{
		$request = request();
		$type = $request->type;
		$response = responseApp();
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
				$response->message($this->loginSuccess())->setValue('data', $this->loginPayload($user, $token))->status(\Illuminate\Http\Response::HTTP_OK);
			} else {
				if ($this->shouldAllowOnlyActiveUsers() && !$user->active) {
					$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($this->deniedAccess());
				}
				$token = $this->guard()->attempt($this->credentials($request));
				if (!$token)
					$response->message($this->loginFailed())->status(\Illuminate\Http\Response::HTTP_UNAUTHORIZED);
				else
					$response->message($this->loginSuccess())->setValue('data', $this->loginPayload($user, $token));
			}
		} catch (OtpMismatchException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_UNAUTHORIZED)->message($exception->getMessage());
		} catch (OtpPushException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message(__('strings.customer.not-found'));
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function register () : \Illuminate\Http\JsonResponse
	{
		$request = request();
		$type = $request->type;
		$response = responseApp();
		$payload = null;
		try {
			$payload = $this->requestValid($request, $this->rulesRegister());
			$conditions = $this->conditionsExists(request());
			$this->throwIfFound($conditions);
			if (!$this->shouldVerifyOtpBeforeRegister()) {
				throw_if((is_null($userOtp = $this->otpTarget()::find($payload['mobile']))), new OtpNotFoundException());
				throw_if(($payload['otp'] != $userOtp->getOtp()), new OtpMismatchException());
			}
			$user = $this->create($request);
			$token = $this->generateToken($user);
			if (!$token)
				$response->message($this->registerFailed())->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
			else
				$response->message($this->registerSuccess())->status(\Illuminate\Http\Response::HTTP_CREATED)->setValue('data', $this->registerPayload($user, $token));
		} catch (OtpNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage());
		} catch (OtpMismatchException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_UNAUTHORIZED)->message($exception->getMessage());
		} catch (OtpPushException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} catch (ResourceConflictException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_CONFLICT)->message($this->registerTaken());
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function socialLogin () : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rulesSocialLogin());
			$user = $this->authTarget()::where('email', $validated['email'])->first();
			if ($user == null) {
				$validated['password'] = Hash::make('12345678');
				$user = $this->authTarget()::create($validated);
			}
			$token = $this->generateToken($user);
			$response->message($this->loginSuccess())->setValue('data', $this->registerPayload($user, $token))->status(\Illuminate\Http\Response::HTTP_OK);
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected abstract function rulesSocialLogin () : array;
}