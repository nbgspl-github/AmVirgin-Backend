<?php

namespace App\Http\Controllers\App\Customer\Auth;

use App\Http\Controllers\App\Auth\BaseAuth;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Claims\Custom;

class AuthController extends BaseAuth {

	protected function authTarget() {
		return Customer::class;
	}

	protected function exists(Request $request) {
		return Customer::where(['email' => $request->email])->first() != null;
	}

	protected function loginPayload(Model $user, string $token) {

	}

	protected function login(Request $request) {

	}

	protected function logout(Request $request) {

	}

	protected function refreshToken(Request $request) {

	}

	protected function registerPayload(Model $user, string $token) {

	}

	protected function register(Request $request) {

	}
}