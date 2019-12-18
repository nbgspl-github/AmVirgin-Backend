<?php

namespace App\Http\Controllers;

use App\Exceptions\OtpIncorrectException;
use App\Exceptions\OtpPushException;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\App\BaseAuthController;
use App\Traits\FluentResponse;
use App\Traits\GuestOtpVerificationSupport;
use Exception;

/**
 * [RoadMap]
 * 1.) Check if user is already registered with us. [mobile or email] [status: 404 or 409]
 * 2.) If he's already registered, we send an otp to his registered mobile number. Later on with the received otp he can proceed with login.
 * 3.) Else we initiate registration and allow the user to fill in his details. We hold the details temporarily while we generate an otp and allow him to verify the same.
 * 4.) If the otp he gave us matches the one we generated for that mobile number, we take his details and stick them up in the DB.
 * 5.) As the last step, we provide basic details and auth token in response.
 * @package App\Http\Controllers
 */
abstract class TwoFactorBaseAuthController extends BaseAuthController{
	use FluentResponse;
	use GuestOtpVerificationSupport;

	protected abstract function otpTarget(): string;

	protected function shouldVerifyOtpBeforeRegister(): bool{
		return true;
	}

	protected function shouldVerifyOtpBeforeLogin(): bool{
		return true;
	}

	protected function exists(){
		$request = request();
		$response = $this->response();
		$payload = null;
		try {
			$payload = $this->requestValid($request, $this->rulesExists());
			$conditions = $this->conditionsExists(request());
			$user = $this->throwIfNotFound($conditions);
			$otp = $user->sendVerificationOtp();
			$user->setOtp($otp)->save();
		}
		catch (OtpPushException $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		catch (ResourceNotFoundException $exception) {
			try {
				$otp = $this->sendGuestOtp($payload['mobile']);
				$this->otpTarget()::createOrUpdate([
					'mobile' => $payload['mobile'],
					'otp' => $otp,
				]);
				$response->status(HttpResourceNotFound);
			}
			catch (OtpPushException $exception) {
				$response->status(HttpServerError)->message($exception->getMessage());
			}
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat);
		}
		catch (Exception $exception) {
			$response->status(HttpServerError);
		}
		finally {
			return $response->send();
		}
	}

	protected function login(){
		$request = request();
		$response = $this->response();
		try {
			$payload = $this->requestValid($request, $this->rulesLogin());
			$conditions = $this->conditionsLogin(request());
			$user = $this->throwIfNotFound($conditions);
			throw_if(($payload['otp'] != $user->getOtp()), new OtpIncorrectException());
			if ($this->shouldAllowOnlyActiveUsers() && !$user->isActive()) {
				$response->status(HttpDeniedAccess)->message($this->deniedAccess());
			}
			$token = $this->guard()->attempt($this->credentials($request));
			if (!$token)
				$response->message($this->loginFailed())->status(HttpUnauthorized);
			else
				$response->success()->message($this->loginSuccess())->setValue('data', $this->loginPayload($user, $token));
		}
		catch (OtpPushException $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		catch (ResourceNotFoundException $exception) {
			$response->status(HttpResourceNotFound);
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat);
		}
		catch (Exception $exception) {
			$response->status(HttpServerError);
		}
		finally {
			return $response->send();
		}
	}

	protected function register(){
		$request = request();
		$response = $this->response();
		try {
			$payload = $this->requestValid($request, $this->rulesRegister());
			$conditions = $this->conditionsLogin(request());
			$user = $this->throwIfNotFound($conditions);
			throw_if(($payload['otp'] != $user->getOtp()), new OtpIncorrectException());
			if ($this->shouldAllowOnlyActiveUsers() && !$user->isActive()) {
				$response->status(HttpDeniedAccess)->message($this->deniedAccess());
			}
			$token = $this->guard()->attempt($this->credentials($request));
			if (!$token)
				$response->message($this->loginFailed())->status(HttpUnauthorized);
			else
				$response->success()->message($this->loginSuccess())->setValue('data', $this->loginPayload($user, $token));
		}
		catch (OtpPushException $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		catch (ResourceNotFoundException $exception) {
			$response->status(HttpResourceNotFound);
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat);
		}
		catch (Exception $exception) {
			$response->status(HttpServerError);
		}
		finally {
			return $response->send();
		}
	}
}