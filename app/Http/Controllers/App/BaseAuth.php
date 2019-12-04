<?php
/**
 * Copyright (c) 2019. Aviral Singh
 */

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Base\AppController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class BaseAuth extends AppController {
	use AuthenticatesUsers;

	protected $authTarget;

	public function __construct() {

	}

	protected abstract function authTarget();

	protected function check($conditions) {
		return $this->authTarget::where($conditions)->first();
	}

	protected function verified(Model $user) {
		return $user->account_verified;
	}

	protected function generateAuthToken(Request $request, Model $user) {
		return auth()->guard('api')->attempt(['email' => $user->email, 'password' => $request->password]);
	}

	protected function generateToken(Model $user) {
		return JWTAuth::fromUser($user);
	}

	protected abstract function exists(Request $request);

	protected abstract function login(Request $request);

	protected abstract function logout(Request $request);

	protected abstract function refreshToken(Request $request);

	protected abstract function loginPayload(Model $user, string $token);

	protected abstract function registerPayload(Model $user, string $token);

	protected abstract function register(Request $request);
}