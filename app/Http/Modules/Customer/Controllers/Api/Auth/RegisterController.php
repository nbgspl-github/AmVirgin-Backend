<?php

namespace App\Http\Modules\Customer\Controllers\Api\Auth;

use App\Http\Modules\Customer\Requests\Auth\RegisterRequest;
use App\Models\Auth\Customer;
use Illuminate\Support\Facades\Hash;

class RegisterController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function register (RegisterRequest $request) : \Illuminate\Http\JsonResponse
	{
		$customer = $this->customerExists($request->only(['email', 'mobile']));
		if (!$customer) {
			$verified = $this->verifyOneTimePassword($request->otp, $request->mobile);
			if ($verified) {
				$customer = $this->create($request->except('otp'));
				return $this->sendRegisterSuccessResponse($customer);
			}
			return $this->sendOtpVerificationFailedResponse();
		}
		return $this->sendCustomerAlreadyRegisteredResponse();
	}

	protected function customerExists (array $credentials) : bool
	{
		return Customer::findByAny($credentials) != null;
	}

	protected function sendCustomerAlreadyRegisteredResponse () : \Illuminate\Http\JsonResponse
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

	protected function sendRegisterSuccessResponse (Customer $customer) : \Illuminate\Http\JsonResponse
	{
		$token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($customer);
		return responseApp()->prepare([
			(new \App\Resources\Auth\Customer\AuthProfileResource($customer))->token($token)
		], \Illuminate\Http\Response::HTTP_OK, __('auth.register.success'), 'data');
	}

	protected function verifyOneTimePassword (string $otp, string $mobile) : bool
	{
		return \App\Models\CustomerOtp::findBy(['mobile' => $mobile, 'otp' => $otp]) != null;
	}

	/**
	 * @param array $payload
	 * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Customer
	 */
	protected function create (array $payload)
	{
		$payload['password'] = Hash::make($payload['password']);
		return Customer::query()->create($payload);
	}
}