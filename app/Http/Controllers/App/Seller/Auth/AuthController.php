<?php

namespace App\Http\Controllers\App\Seller\Auth;

use App\Exceptions\ResourceConflictException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\App\Auth\BaseAuth;
use App\Http\Resources\Auth\Seller\LoginResource;
use App\Interfaces\Roles;
use App\Interfaces\StatusCodes;
use App\Models\Seller;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseAuth {
	use ValidatesRequest;
	use FluentResponse;

	/**
	 * @var array
	 */
	protected $rules;

	/**
	 * AuthController constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->rules = config('rules.auth.seller');
	}

	protected function authTarget() {
		return Seller::class;
	}

	protected function exists(Request $request) {
		return $this->authTarget()::where(['email' => $request->email])->first() != null;
	}

	protected function loginPayload($seller, string $token) {
		return [
			'sellerId' => $seller->getKey(),
			'name' => $seller->getName(),
			'type' => Roles::Seller,
			'token' => $token,
		];
	}

	protected function login(Request $request) {
		try {
			$this->requestValid($request, $this->rules['login']);
			$seller = $this->throwIfNotFound(['email' => $request->email]);
			$token = auth()->attempt($this->credentials($request));
			if (!$token)
				return $this->failed()->message(__('strings.seller.auth.login.failed'))->send();
			else
				return $this->success()->message(__('strings.seller.auth.login.success'))->setResource(new LoginResource($seller))->send();
		}
		catch (ModelNotFoundException $exception) {
			return $this->failed()->status(StatusCodes::ResourceNotFound)->send();
		}
		catch (ValidationException $exception) {
			return $this->failed()->message($exception->getError())->status(StatusCodes::InvalidRequestFormat)->send();
		}
		catch (Exception $exception) {
			return $this->error()->message($exception->getMessage())->send();
		}
	}

	protected function logout(Request $request) {

	}

	protected function refreshToken(Request $request) {

	}

	protected function registerPayload(Model $user, string $token) {

	}

	protected function register(Request $request) {
		try {
			$this->requestValid($request, $this->rules['register']);
			$seller = $this->throwIfFound(['email' => $request->email]);
			$token = $this->generateToken($seller);
			if (!$token)
				return $this->failed()->message(__('strings.seller.auth.login.failed'))->send();
			else
				return $this->success()->message(__('strings.seller.auth.login.success'))->setResource(new LoginResource($seller))->send();
		}
		catch (ResourceConflictException $exception) {
			return $this->failed()->status(StatusCodes::ResourceAlreadyExists)->send();
		}
		catch (ValidationException $exception) {
			return $this->failed()->message($exception->getError())->status(StatusCodes::InvalidRequestFormat)->send();
		}
		catch (Exception $exception) {
			return $this->error()->message($exception->getMessage())->send();
		}
	}

	protected function guard() {
		return Auth::guard('seller-api');
	}

	protected function credentials(Request $request) {
		return [
			'email' => $request->email,
			'password' => $request->password,
		];
	}
}