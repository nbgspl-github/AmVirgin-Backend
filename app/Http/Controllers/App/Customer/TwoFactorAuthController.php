<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\TwoFactorBaseAuthController;
use App\Models\Customer;
use App\Models\CustomerOtp;
use Illuminate\Support\Facades\Auth;

/**
 * Auth functionality for Customer.
 * @package App\Http\Controllers\App\Customer
 */
class TwoFactorAuthController extends TwoFactorBaseAuthController{
	protected $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = config('rules.auth.customer');
	}

	protected function authTarget(): string{
		return Customer::class;
	}

	protected function rulesExists(): array{
		return $this->rules['exists'];
	}

	protected function rulesLogin(): array{
		return $this->rules['login'];
	}

	protected function rulesRegister(){
		return $this->rules['register'];
	}

	protected function otpTarget(): string{
		return CustomerOtp::class;
	}

	protected function guard(){
		return Auth::guard('customer-api');
	}

	protected function shouldVerifyOtpBeforeRegister(): bool{
		return true;
	}

	protected function shouldVerifyOtpBeforeLogin(): bool{
		return true;
	}
}