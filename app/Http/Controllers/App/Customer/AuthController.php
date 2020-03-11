<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\App\BaseAuthController;
use App\Http\Resources\Auth\Customer\AuthProfileResource;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

/**
 * Auth functionality for Customer.
 * @package App\Http\Controllers\App\Customer\Auth
 */
class AuthController extends BaseAuthController{
	protected $ruleSet;

	public function __construct(){
		parent::__construct();
		$this->ruleSet = config('rules.auth.customer');
	}

	protected function authTarget(): string{
		return Customer::class;
	}

	protected function rulesExists(){
		return $this->ruleSet['exists'];
	}

	protected function rulesLogin(){
		return $this->ruleSet['login'];
	}

	protected function rulesRegister() {
		return $this->ruleSet['register'];
	}

	protected function guard() {
		return Auth::guard('customer-api');
	}

	public function profile() {
		return new AuthProfileResource($this->guard()->user());
	}

	protected function rulesUpdateProfile() {
		// TODO: Implement rulesUpdateProfile() method.
	}

	protected function rulesUpdateAvatar() {
		// TODO: Implement rulesUpdateAvatar() method.
	}
}