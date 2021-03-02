<?php

namespace App\Http\Modules\Customer\Controllers\Api\Auth;

use App\Http\Modules\Customer\Requests\Auth\LoginRequest;
use App\Models\Auth\Customer;

class LoginController extends \App\Http\Modules\Shared\Controllers\Api\AuthController
{
	public function __construct ()
	{
		parent::__construct(app(Customer::class));
		$this->middleware(AUTH_CUSTOMER)->only('logout');
	}

	public function login (LoginRequest $request) : \Illuminate\Http\JsonResponse
	{
		$exists = parent::exists($request);
		if ($exists) {
			$customer = $this->findCustomerByLoginType($request);
			if ($this->canLogin($customer)) {
				$verified = $this->verify($customer, $request);
				return ($verified)
					? $this->sendLoginSuccessResponse($customer)
					: $this->sendLoginFailedResponse($request->type);
			}
			return $this->sendCustomerRestrictedResponse();
		}
		return $this->sendCustomerNotFoundResponse();
	}

	public function logout () : \Illuminate\Http\JsonResponse
	{
		$this->guard()->logout();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_NO_CONTENT, __('auth.logout.success'), 'data'
		);
	}

	protected function sendLoginSuccessResponse (Customer $customer) : \Illuminate\Http\JsonResponse
	{
		$token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($customer);
		return responseApp()->prepare(
			(new \App\Resources\Auth\Customer\AuthProfileResource($customer))->token($token), \Illuminate\Http\Response::HTTP_OK, __('auth.login.success'), 'data');
	}

	protected function sendLoginFailedResponse ($type) : \Illuminate\Http\JsonResponse
	{
		return ($type == self::TYPE_OTP)
			? $this->sendOtpVerificationFailedResponse()
			: $this->sendInvalidCredentialsResponse();
	}

	protected function sendCustomerNotFoundResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
            null, \Illuminate\Http\Response::HTTP_NOT_FOUND, __('auth.user.not_found'), 'data'
        );
	}

	protected function sendCustomerRestrictedResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_FORBIDDEN, __('auth.user.restricted'), 'data'
		);
	}

	protected function findCustomerByLoginType (\Illuminate\Http\Request $request)
	{
		$query = null;
		switch ($request->type) {
			case self::TYPE_EMAIL:
				$query = ['email' => $request->email];
				break;

			case self::TYPE_MOBILE:
			case self::TYPE_OTP:
				$query = ['mobile' => $request->mobile];
				break;
		}
		return Customer::findBy($query);
	}

	protected function canLogin (Customer $customer) : bool
	{
		return $customer->active;
	}

	protected function verifyWithOneTimePassword (Customer $customer, string $otp) : bool
	{
		return $customer->verify($otp);
	}

	protected function credentials (\Illuminate\Http\Request $request) : array
	{
		if ($request->type == self::TYPE_MOBILE) {
			return [
				'mobile' => $request->mobile,
				'password' => $request->password,
			];
		} else {
			return [
				'email' => $request->email,
				'password' => $request->password,
			];
		}
	}

	protected function verify (Customer $customer, \Illuminate\Http\Request $request) : bool
	{
		return ($request->type == self::TYPE_OTP)
			? $this->verifyWithOneTimePassword($customer, $request->otp)
			: $this->verifyWithCredentials($customer, $this->credentials($request));
	}

	protected function verifyWithCredentials (Customer $customer, array $credentials) : bool
	{
		return $this->guard()->attempt($credentials);
	}

	protected function sendOtpVerificationFailedResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_UNAUTHORIZED, __('auth.otp.failed_verification'), 'data'
		);
	}

	protected function sendInvalidCredentialsResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_UNAUTHORIZED, __('auth.user.invalid_credentials'), 'data'
		);
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}