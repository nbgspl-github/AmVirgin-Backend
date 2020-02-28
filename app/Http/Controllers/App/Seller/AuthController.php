<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\App\BaseAuthController;
use App\Http\Resources\Auth\Seller\AuthProfileResource;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;

/**
 * Auth functionality for Seller.
 * @package App\Http\Controllers\App\Seller\Auth
 */
class AuthController extends BaseAuthController {
	/**
	 * @var array
	 */
	protected $ruleSet;

	/**
	 * AuthController constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->ruleSet = config('rules.auth.seller');
	}

	protected function authTarget(): string {
		return Seller::class;
	}

	protected function rulesExists() {
		return $this->ruleSet['exists'];
	}

	protected function rulesLogin() {
		return $this->ruleSet['login'];
	}

	protected function rulesRegister() {
		return $this->ruleSet['register'];
	}

	protected function rulesUpdateAvatar() {
		return $this->ruleSet['update']['avatar'];
	}

	protected function rulesUpdateProfile() {
		return $this->ruleSet['update']['profile'];
	}

	public function profile() {
		return new AuthProfileResource($this->guard()->user());
	}

	protected function guard() {
		return Auth::guard('seller-api');
	}

	protected function shouldAllowOnlyActiveUsers(): bool {
		return true;
	}
}