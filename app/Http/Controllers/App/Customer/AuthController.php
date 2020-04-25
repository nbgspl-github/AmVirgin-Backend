<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\App\BaseAuthController;
use App\Resources\Auth\Customer\AuthProfileResource;
use App\Models\Auth\Customer;
use Illuminate\Database\Eloquent\Model;
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

	protected function rulesRegister(){
		return $this->ruleSet['register'];
	}

	protected function guard(){
		return Auth::guard('customer-api');
	}

	public function profile(){
		return new AuthProfileResource($this->guard()->user());
	}

	protected function rulesUpdateProfile(){

	}

	protected function rulesUpdateAvatar(){
		return [
			'avatar' => ['bail', 'required', 'image', 'min:1', 'max:4096'],
		];
	}
}