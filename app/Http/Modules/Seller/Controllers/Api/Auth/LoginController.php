<?php

namespace App\Http\Modules\Seller\Controllers\Api\Auth;

use App\Http\Modules\Seller\Requests\Auth\LoginRequest;
use App\Models\Auth\Customer;
use App\Models\Auth\Seller;

class LoginController extends \App\Http\Modules\Shared\Controllers\Api\AuthController
{
	public function __construct ()
	{
		parent::__construct(app(Seller::class));
		$this->middleware(AUTH_SELLER)->only('logout');
	}

	public function login (LoginRequest $request) : \Illuminate\Http\JsonResponse
	{
		$exists = parent::exists($request);
		if ($exists) {
			$seller = $this->findSellerByLoginType($request);
			if ($this->canLogin($seller)) {
				$verified = $this->verify($seller, $request);
				return ($verified)
					? $this->sendLoginSuccessResponse($seller)
					: $this->sendLoginFailedResponse($request->type);
			}
			return $this->sendSellerRestrictedResponse();
		}
		return $this->sendSellerNotFoundResponse();
	}

	public function logout () : \Illuminate\Http\JsonResponse
	{
		$this->guard()->logout();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_NO_CONTENT, __('auth.logout.success'), 'data'
		);
	}

	protected function sendLoginSuccessResponse (Seller $seller) : \Illuminate\Http\JsonResponse
	{
		$token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($seller);
		return responseApp()->prepare([
			(new \App\Resources\Auth\Seller\AuthProfileResource($seller))->token($token)
		], \Illuminate\Http\Response::HTTP_OK, __('auth.login.success'), 'data');
	}

	protected function sendLoginFailedResponse ($type) : \Illuminate\Http\JsonResponse
	{
		return ($type == self::TYPE_OTP)
			? $this->sendOtpVerificationFailedResponse()
			: $this->sendInvalidCredentialsResponse();
	}

	protected function sendSellerNotFoundResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_CONFLICT, __('auth.user.not_found'), 'data'
		);
	}

	protected function sendSellerRestrictedResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_FORBIDDEN, __('auth.user.restricted'), 'data'
		);
	}

	protected function findSellerByLoginType (\Illuminate\Http\Request $request)
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

	protected function canLogin (Seller $seller) : bool
	{
		return $seller->active;
	}

	protected function verifyWithOneTimePassword (Seller $seller, string $otp) : bool
	{
		return $seller->verify($otp);
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

	protected function verify (Seller $seller, \Illuminate\Http\Request $request) : bool
	{
		return ($request->type == self::TYPE_OTP)
			? $this->verifyWithOneTimePassword($seller, $request->otp)
			: $this->verifyWithCredentials($seller, $this->credentials($request));
	}

	protected function verifyWithCredentials (Seller $seller, array $credentials) : bool
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