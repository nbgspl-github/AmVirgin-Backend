<?php

namespace App\Http\Modules\Seller\Controllers\Api\Auth;

use App\Http\Modules\Seller\Requests\Auth\RegisterRequest;
use App\Models\Auth\Seller;
use App\Resources\Auth\Seller\AuthProfileResource;
use Illuminate\Support\Facades\Hash;

class RegisterController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function register (RegisterRequest $request) : \Illuminate\Http\JsonResponse
	{
		$customer = $this->sellerExists($request->only(['email', 'mobile']));
		if (!$customer) {
			$verified = $this->verifyOneTimePassword($request->otp, $request->mobile);
			if ($verified) {
				$customer = $this->create($request->except('otp'));
				return $this->sendRegisterSuccessResponse($customer);
			}
			return $this->sendOtpVerificationFailedResponse();
		}
		return $this->sendSellerAlreadyRegisteredResponse();
	}

	protected function sellerExists (array $credentials) : bool
	{
		return Seller::findByAny($credentials) != null;
	}

	protected function sendSellerAlreadyRegisteredResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_NO_CONTENT, __('auth.register.taken'), 'data'
		);
	}

	protected function sendOtpVerificationFailedResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_UNAUTHORIZED, __('auth.otp.failed_verification'), 'data'
		);
	}

	protected function sendRegisterSuccessResponse (Seller $seller) : \Illuminate\Http\JsonResponse
	{
		$token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($seller);
		return responseApp()->prepare([
			(new AuthProfileResource($seller))->token($token)
		], \Illuminate\Http\Response::HTTP_OK, __('auth.register.success'), 'data');
	}

	protected function verifyOneTimePassword (string $otp, string $mobile) : bool
	{
		return \App\Models\SellerOtp::findBy(['mobile' => $mobile, 'otp' => $otp]) != null;
	}

	/**
	 * @param array $payload
	 * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Seller
	 */
	protected function create (array $payload)
	{
		$payload['password'] = Hash::make($payload['password']);
		return Seller::query()->create($payload);
	}
}