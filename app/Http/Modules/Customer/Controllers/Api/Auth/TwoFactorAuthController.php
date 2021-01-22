<?php

namespace App\Http\Modules\Customer\Controllers\Api\Auth;

use App\Http\Modules\Shared\Controllers\Auth\TwoFactorBaseAuthController;
use App\Models\Auth\Customer;
use App\Models\CustomerOtp;
use Illuminate\Support\Facades\Auth;

/**
 * Auth functionality for Customer.
 * @package App\Http\Controllers\App\Customer
 */
class TwoFactorAuthController extends TwoFactorBaseAuthController
{
	protected $ruleSet;

	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet = config('rules.auth.customer');
	}

	protected function authTarget () : string
	{
		return Customer::class;
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
		return CustomerOtp::class;
	}

	protected function guard ()
	{
		return Auth::guard('customer-api');
	}

	protected function shouldVerifyOtpBeforeRegister () : bool
	{
		return true;
	}

	protected function shouldVerifyOtpBeforeLogin () : bool
	{
		return true;
	}

	protected function rulesUpdateProfile ()
	{

	}

	protected function rulesUpdateAvatar ()
	{

	}

	protected function loginPayload (\App\Library\Database\Eloquent\Model $user, string $token) : array
	{
		return (new \App\Resources\Auth\Customer\AuthProfileResource($user))->token($token)->toArray(null);
	}

	protected function registerPayload (\App\Library\Database\Eloquent\Model $user, string $token) : array
	{
		return (new \App\Resources\Auth\Customer\AuthProfileResource($user))->token($token)->toArray(null);
	}

	protected function rulesUpdatePassword () : array
	{

	}

	protected function rulesSocialLogin () : array
	{
		return [
			'email' => 'bail|required|email|max:255',
			'name' => 'bail|required|string|max:255'
		];
	}
}