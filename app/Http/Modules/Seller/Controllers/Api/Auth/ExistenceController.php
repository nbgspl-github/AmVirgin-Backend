<?php

namespace App\Http\Modules\Seller\Controllers\Api\Auth;

use App\Http\Modules\Shared\Requests\Auth\ExistsRequest;
use App\Models\Auth\Customer;
use App\Models\Auth\Seller;

/**
 * Class ExistenceController
 * @package App\Http\Modules\Seller\Controllers\Api\Auth
 */
class ExistenceController extends \App\Http\Modules\Shared\Controllers\Api\AuthController
{
	public function __construct ()
	{
		parent::__construct(app(Seller::class));
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
				return $this->sendSellerFoundResponse();
			} else {
				return $this->sendSellerNotFoundResponse();
			}
		}
	}

	protected function sendRegisterOtp (\Illuminate\Http\Request $request) : \Illuminate\Http\JsonResponse
	{
		$otp = Seller::sendGuestOtpMessage($request->mobile);
		\App\Models\SellerOtp::query()->updateOrCreate(['mobile' => $request->mobile], [
			'otp' => $otp
		]);
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_CONFLICT, __('auth.otp.guest'), 'data'
		);
	}

	protected function sendLoginOtp (\Illuminate\Http\Request $request) : \Illuminate\Http\JsonResponse
	{
		$seller = Seller::findBy(['mobile' => $request->mobile]);
		$otp = $seller->sendOtpMessage();
		$seller->update([
			'otp' => $otp
		]);
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_CONFLICT, __('auth.otp.login'), 'data'
		);
	}

	protected function sendSellerFoundResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_CONFLICT, __('auth.user.found'), 'data'
		);
	}

	protected function sendSellerNotFoundResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_CONFLICT, __('auth.user.not_found'), 'data'
		);
	}
}