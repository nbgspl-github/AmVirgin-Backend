<?php

namespace App\Http\Modules\Customer\Controllers\Api\Auth;

use App\Http\Modules\Shared\Requests\Auth\ExistsRequest;
use App\Models\Auth\Customer;

/**
 * Auth functionality for Customer.
 * @package App\Http\Controllers\App\Customer\Auth
 */
class ExistenceController extends \App\Http\Modules\Shared\Controllers\Api\AuthController
{
	public function __construct ()
	{
		parent::__construct(app(Customer::class));
	}

	public function exists (ExistsRequest $request) : \Illuminate\Http\JsonResponse
	{
		$exists = parent::exists($request);
		if ($request->type == self::TYPE_OTP) {
			if ($exists) {
				return $this->sendLoginOtp($request);
			} else {
				return $this->sendRegisterOtp($request);
			}
		} else {
			if ($exists) {
				return $this->sendCustomerFoundResponse();
			} else {
				return $this->sendCustomerNotFoundResponse();
			}
		}
	}

	protected function sendRegisterOtp (\Illuminate\Http\Request $request) : \Illuminate\Http\JsonResponse
	{
		$otp = Customer::sendGuestOtpMessage($request->mobile);
		\App\Models\CustomerOtp::query()->updateOrCreate(['mobile' => $request->mobile], [
			'otp' => $otp
		]);
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_CONFLICT, __('auth.otp.guest'), 'data'
		);
	}

	protected function sendLoginOtp (\Illuminate\Http\Request $request) : \Illuminate\Http\JsonResponse
	{
		$customer = Customer::findBy(['mobile' => $request->mobile]);
		$otp = $customer->sendOtpMessage();
		$customer->update([
			'otp' => $otp
		]);
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_CONFLICT, __('auth.otp.login'), 'data'
		);
	}

	protected function sendCustomerFoundResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_CONFLICT, __('auth.user.found'), 'data'
		);
	}

	protected function sendCustomerNotFoundResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_CONFLICT, __('auth.user.not_found'), 'data'
		);
	}
}