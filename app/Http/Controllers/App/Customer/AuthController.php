<?php

namespace App\Http\Controllers\App\Customer;

use App\Exceptions\ResourceConflictException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\App\Auth\BaseAuth;
use App\Http\Resources\Auth\Seller\AuthProfileResource;
use App\Interfaces\StatusCodes;
use App\Models\Customer;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Auth functionality for Customer.
 * @package App\Http\Controllers\App\Customer\Auth
 */
class AuthController extends BaseAuth{
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
		$this->rules = config('rules.auth.customer');
	}

	protected function authTarget() {
		return Customer::class;
	}

	protected function exists(Request $request) {
		try {
			$this->requestValid($request, $this->rules['exists']);
			$conditions = ($request->has('mobile') && !empty($request->mobile)) ? $conditions = ['mobile' => $request->mobile] : $conditions = ['email' => $request->email];
			$seller = $this->check($conditions);
			if ($seller != null) {
				return $this->success()->status(StatusCodes::Okay)->message(__('strings.customer.auth.login.check'))->send();
			}
			else {
				return $this->success()->status(StatusCodes::ResourceNotFound)->message(__('strings.customer.auth.login.check-failed'))->send();
			}
		}
		catch (ValidationException $exception) {
			return $this->failed()->status(StatusCodes::InvalidRequestFormat)->message($exception->getError())->send();
		}
		catch (Exception $exception) {
			return $this->error()->message($exception->getMessage())->send();
		}
	}

	protected function loginPayload($user, string $token) {
		return [
			'name' => $user->getName(),
			'email' => $user->getEmail(),
			'mobile' => $user->getMobile(),
			'token' => $token,
		];
	}

	protected function login(Request $request) {
		try {
			$this->requestValid($request, $this->rules['login']);
			$conditions = ($request->has('mobile') && !empty($request->mobile)) ? $conditions = ['mobile' => $request->mobile] : $conditions = ['email' => $request->email];
			$seller = $this->throwIfNotFound($conditions);
			$token = $this->guard()->attempt($this->credentials($request));
			if (!$token)
				return $this->failed()->message(__('strings.customer.auth.login.failed'))->status(StatusCodes::Unauthorized)->send();
			else
				return $this->success()->message(__('strings.customer.auth.login.success'))->setValue('data', $this->loginPayload($seller, $token))->send();
		}
		catch (ModelNotFoundException $exception) {
			return $this->failed()->status(StatusCodes::ResourceNotFound)->send();
		}
		catch (ValidationException $exception) {
			return $this->failed()->message($exception->getError())->status(StatusCodes::InvalidRequestFormat)->send();
		}
		catch (Exception $exception) {
			return $this->error()->message($exception->getTraceAsString())->send();
		}
	}

	protected function logout(Request $request) {
		try {
			$this->guard()->logout();
			return $this->success()->message(__('strings.customer.auth.logout.success'))->send();
		}
		catch (Exception $exception) {
			return $this->error()->message($exception->getMessage())->send();
		}
	}

	protected function registerPayload($user, string $token) {
		return [
			'name' => $user->getName(),
			'email' => $user->getEmail(),
			'mobile' => $user->getMobile(),
			'token' => $token,
		];
	}

	protected function register(Request $request) {
		try {
			$this->requestValid($request, $this->rules['register']);
			$this->throwIfFound(['email' => $request->email]);
			$seller = Customer::create([
				'name' => $request->name,
				'email' => $request->email,
				'mobile' => $request->mobile,
				'password' => Hash::make($request->password),
			]);
			$token = $this->generateToken($seller);
			if (!$token)
				return $this->failed()->message(__('strings.customer.auth.register.failed'))->send();
			else
				return $this->success()->message(__('strings.customer.auth.register.success'))->status(StatusCodes::Created)->setValue('data', $this->registerPayload($seller, $token))->send();
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

	protected function credentials(Request $request) {
		if ($request->exists('mobile')) {
			return [
				'mobile' => $request->mobile,
				'password' => $request->password,
			];
		}
		else {
			return [
				'email' => $request->email,
				'password' => $request->password,
			];
		}
	}

	protected function guard() {
		return Auth::guard('customer-api');
	}

	protected function profile(Request $request) {
		return new AuthProfileResource($this->guard()->user());
	}
}