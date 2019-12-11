<?php
/**
 * Copyright (c) 2019. Aviral Singh
 */

namespace App\Http\Controllers\App\Auth;

use App\Exceptions\ResourceConflictException;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\Auth\Seller\AuthProfileResource;
use App\Interfaces\StatusCodes;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class BaseAuthController extends BaseController{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	use AuthenticatesUsers;
	use ValidatesRequest;
	use FluentResponse;
	use ResponseTrait;

	public function __construct(){

	}

	protected abstract function authTarget();

	protected abstract function rulesExists();

	protected abstract function rulesLogin();

	protected abstract function rulesRegister();

	protected function checkSuccess(){
		return __('strings.auth.check.success');
	}

	protected function checkFailed(){
		return __('strings.auth.check.failed');
	}

	protected function loginSuccess(){
		return __('strings.auth.login.success');
	}

	protected function loginFailed(){
		return __('strings.auth.login.failed');
	}

	protected function registerSuccess(){
		return __('strings.auth.register.success');
	}

	protected function registerFailed(){
		return __('strings.auth.register.failed');
	}

	protected function registerTaken(){
		return __('strings.auth.register.taken');
	}

	protected function logoutSuccess(){
		return __('strings.auth.logout.success');
	}

	protected function logoutFailed(){
		return __('strings.auth.logout.failed');
	}

	protected function deniedAccess(){
		return __('strings.auth.denied');
	}

	protected function shouldAllowOnlyActiveUsers(){
		return false;
	}

	protected function conditionsExists(Request $request){
		$hasMobile = ($request->has('mobile') && !empty($request->mobile));
		$hasEmail = ($request->has('email') && !empty($request->email));
		if ($hasEmail && $hasMobile) {
			return function ($query) use ($request){
				$query->where('mobile', $request->mobile)->where('email', $request->email);
			};
		}
		else if ($hasEmail) {
			return function ($query) use ($request){
				$query->where('email', $request->email);
			};
		}
		else {
			return function ($query) use ($request){
				$query->where('mobile', $request->mobile);
			};
		}
	}

	protected function conditionsLogin(Request $request){
		return $this->conditionsExists($request);
	}

	protected function conditionsRegister(Request $request){
		return $this->conditionsExists($request);
	}

	protected function check($conditions){
		return $this->authTarget()::where($conditions)->first();
	}

	protected function throwIfNotFound($conditions){
		$model = $this->authTarget()::where($conditions)->first();
		if ($model == null)
			throw new ResourceNotFoundException();
		else
			return $model;
	}

	protected function throwIfFound($conditions){
		$model = $this->authTarget()::where($conditions)->first();
		if ($model != null)
			throw new ResourceConflictException();
		else
			return $model;
	}

	protected function verified(Model $user){
		return $user->account_verified;
	}

	protected function generateAuthToken(Request $request, Model $user){
		return auth()->guard('api')->attempt(['email' => $user->email, 'password' => $request->password]);
	}

	protected function generateToken(Model $user){
		return JWTAuth::fromUser($user);
	}

	protected function exists(Request $request){
		try {
			$this->requestValid($request, $this->rulesExists());
			$conditions = $this->conditionsExists($request);
			$user = $this->check($conditions);
			if ($user != null) {
				return $this->success()->status(StatusCodes::Okay)->message($this->checkSuccess())->send();
			}
			else {
				return $this->success()->status(StatusCodes::ResourceNotFound)->message($this->checkFailed())->send();
			}
		}
		catch (ValidationException $exception) {
			return $this->failed()->status(StatusCodes::InvalidRequestFormat)->message($exception->getError())->send();
		}
		catch (Exception $exception) {
			return $this->error()->message($exception->getMessage())->send();
		}
	}

	protected function login(Request $request){
		try {
			$this->requestValid($request, $this->rulesLogin());
			$conditions = $this->conditionsLogin($request);
			$seller = $this->throwIfNotFound($conditions);
			if ($this->shouldAllowOnlyActiveUsers() && !$seller->isActive()) {
				return $this->failed()->status(StatusCodes::DeniedAccess)->message($this->deniedAccess())->send();
			}
			$token = $this->guard()->attempt($this->credentials($request));
			if (!$token)
				return $this->failed()->message($this->loginFailed())->status(StatusCodes::Unauthorized)->send();
			else
				return $this->success()->message($this->loginSuccess())->setValue('data', $this->loginPayload($seller, $token))->send();
		}
		catch (ResourceNotFoundException $exception) {
			return $this->failed()->status(StatusCodes::ResourceNotFound)->send();
		}
		catch (ValidationException $exception) {
			return $this->failed()->message($exception->getError())->status(StatusCodes::InvalidRequestFormat)->send();
		}
		catch (Exception $exception) {
			return $this->error()->message($exception->getTraceAsString())->send();
		}
	}

	protected function logout(Request $request){
		try {
			$this->guard()->logout();
			return $this->success()->message($this->logoutSuccess())->send();
		}
		catch (Exception $exception) {
			return $this->error()->message($exception->getMessage())->send();
		}
	}

	protected function loginPayload(Model $user, string $token){
		return [
			'name' => $user->getName(),
			'email' => $user->getEmail(),
			'mobile' => $user->getMobile(),
			'token' => $token,
		];
	}

	protected function registerPayload(Model $user, string $token){
		return [
			'name' => $user->getName(),
			'email' => $user->getEmail(),
			'mobile' => $user->getMobile(),
			'token' => $token,
		];
	}

	protected function register(Request $request){
		try {
			$this->requestValid($request, $this->rulesRegister());
			$this->throwIfFound($this->conditionsRegister($request));
			$user = $this->create($request);
			$token = $this->generateToken($user);
			if (!$token)
				return $this->failed()->message($this->registerFailed())->send();
			else
				return $this->success()->message($this->registerSuccess())->status(StatusCodes::Created)->setValue('data', $this->registerPayload($user, $token))->send();
		}
		catch (ResourceConflictException $exception) {
			return $this->failed()->status(StatusCodes::ResourceAlreadyExists)->message($this->registerTaken())->send();
		}
		catch (ValidationException $exception) {
			return $this->failed()->message($exception->getError())->status(StatusCodes::InvalidRequestFormat)->send();
		}
		catch (Exception $exception) {
			return $this->error()->message($exception->getMessage())->send();
		}
	}

	protected function create(Request $request){
		return $this->authTarget()::create([
			'name' => $request->name,
			'email' => $request->email,
			'mobile' => $request->mobile,
			'password' => Hash::make($request->password),
		]);
	}

	protected function profile(Request $request){
		return new AuthProfileResource($this->guard()->user());
	}

	protected function credentials(Request $request){
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
}