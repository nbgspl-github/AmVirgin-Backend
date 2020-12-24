<?php

namespace App\Http\Modules\Seller\Controllers\Api\Auth;

use App\Http\Modules\Shared\Controllers\Auth\TwoFactorBaseAuthController;
use App\Models\Auth\Seller;
use App\Models\SellerOtp;
use Illuminate\Support\Facades\Auth;

/**
 * Auth functionality for Seller.
 * @package App\Http\Controllers\App\Seller
 */
class TwoFactorAuthController extends TwoFactorBaseAuthController
{
	protected $ruleSet;

	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet = config('rules.auth.seller');
	}

	protected function authTarget () : string
	{
		return Seller::class;
	}

	protected function rulesExists () : array
	{
		return $this->ruleSet['exists'];
	}

	protected function rulesLogin () : array
	{
		return $this->ruleSet['login'];
	}

	protected function rulesRegister ()
	{
		return $this->ruleSet['register'];
	}

	protected function otpTarget () : string
	{
		return SellerOtp::class;
	}

	protected function guard ()
	{
		return Auth::guard('seller-api');
	}

	protected function shouldVerifyOtpBeforeRegister () : bool
	{
		return true;
	}

	protected function shouldVerifyOtpBeforeLogin () : bool
	{
		return true;
	}

	protected function rulesUpdateAvatar ()
	{
		return $this->ruleSet['update']['avatar'];
	}

	protected function rulesUpdateProfile ()
	{
		return $this->ruleSet['update']['profile'];
	}

	protected function loginPayload (\App\Library\Database\Eloquent\AuthEntity $user, string $token)
	{
		return (new \App\Resources\Auth\Seller\AuthProfileResource($user))->token($token);
	}

	protected function registerPayload (\App\Library\Database\Eloquent\AuthEntity $user, string $token)
	{
		return (new \App\Resources\Auth\Seller\AuthProfileResource($user))->token($token);
	}

	protected function rulesUpdatePassword () : array
	{
		// TODO: Implement rulesUpdatePassword() method.
	}

	protected function rulesSocialLogin () : array
	{
		return [
			'email' => 'bail|required|email|max:255',
			'name' => 'bail|required|string|max:255'
		];
	}
}