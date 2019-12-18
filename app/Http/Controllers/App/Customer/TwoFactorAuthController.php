<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\TwoFactorBaseAuthController;
use App\Models\Customer;
use App\Models\CustomerOtp;
use Illuminate\Support\Facades\Auth;

/**
 * Auth functionality for Customer.
 * @package App\Http\Controllers\App\Customer\Auth
 */
class TwoFactorAuthController extends TwoFactorBaseAuthController{
	protected $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = config('rules.auth.customer');
	}

	protected function authTarget(): string{
		// TODO: Implement authTarget() method.
	}

	protected function rulesExists(): array{
		// TODO: Implement rulesExists() method.
	}

	protected function rulesLogin(): array{
		// TODO: Implement rulesLogin() method.
	}

	protected function rulesRegister(){
		// TODO: Implement rulesRegister() method.
	}

	protected function rulesLogin2F(): array{
		// TODO: Implement rulesLogin2F() method.
	}

	protected function rulesPreRegister(): array{
		// TODO: Implement rulesPreRegister() method.
	}

	protected function rulesPostRegister(): array{
		// TODO: Implement rulesPostRegister() method.
	}

	protected function otpTarget(): string{
		// TODO: Implement otpTarget() method.
	}
}