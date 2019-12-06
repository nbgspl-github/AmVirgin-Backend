<?php

namespace App\Http\Controllers\App\Seller\Auth;

use App\Http\Controllers\App\Auth\BaseAuth;
use App\Models\Customer;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuthController extends BaseAuth {
	protected function authTarget() {
		return Seller::class;
	}

	protected function exists(Request $request) {
		return $this->authTarget()::where(['email' => $request->email])->first() != null;
	}

	protected function loginPayload($seller, string $token) {
		/**
		 * @var $seller Seller
		 */
		return [
			'sellerId' => $seller->get,

		];
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