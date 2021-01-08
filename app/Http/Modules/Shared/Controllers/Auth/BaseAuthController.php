<?php
/**
 * Copyright (c) 2019. Aviral Singh
 */

namespace App\Http\Modules\Shared\Controllers\Auth;

use App\Exceptions\ResourceConflictException;
use App\Exceptions\ValidationException;
use App\Http\Modules\Shared\Controllers\BaseController;
use App\Library\Enums\Common\Directories;
use App\Library\Utils\Uploads;
use App\Models\Auth\Seller;
use App\Resources\Auth\Seller\AuthProfileResource;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class BaseAuthController extends BaseController
{
	use AuthenticatesUsers;
	use ValidatesRequest;
	use FluentResponse;

	public function __construct ()
	{
		parent::__construct();
	}

	protected abstract function authTarget (): string;

	protected abstract function rulesExists ();

	protected abstract function rulesLogin ();

	protected abstract function rulesRegister ();

	protected abstract function rulesUpdateProfile ();

	protected abstract function rulesUpdateAvatar ();

	protected abstract function rulesUpdatePassword (): array;

	protected function checkSuccess ()
	{
		return __('strings.auth.check.success');
	}

	protected function checkFailed ()
	{
		return __('strings.auth.check.failed');
	}

	protected function loginSuccess ()
	{
		return __('strings.auth.login.success');
	}

	protected function loginFailed ()
	{
		return __('strings.auth.login.failed');
	}

	protected function registerSuccess ()
	{
		return __('strings.auth.register.success');
	}

	protected function registerFailed ()
	{
		return __('strings.auth.register.failed');
	}

	protected function registerTaken ()
	{
		return __('strings.auth.register.taken');
	}

	protected function logoutSuccess ()
	{
		return __('strings.auth.logout.success');
	}

	protected function logoutFailed ()
	{
		return __('strings.auth.logout.failed');
	}

	protected function deniedAccess ()
	{
		return __('strings.auth.denied');
	}

	protected function shouldAllowOnlyActiveUsers (): bool
	{
		return false;
	}

	protected function conditionsExists (Request $request)
	{
		$hasMobile = ($request->has('mobile') && !empty($request->mobile));
		$hasEmail = ($request->has('email') && !empty($request->email));
		if ($hasEmail && $hasMobile) {
			return function ($query) use ($request) {
				$query->where('mobile', $request->mobile)->where('email', $request->email);
			};
		} else if ($hasEmail) {
			return function ($query) use ($request) {
				$query->where('email', $request->email);
			};
		} else {
			return function ($query) use ($request) {
				$query->where('mobile', $request->mobile);
			};
		}
	}

	protected function conditionsLogin (Request $request)
	{
		return $this->conditionsExists($request);
	}

	protected function conditionsRegister (Request $request)
	{
		return $this->conditionsExists($request);
	}

	protected function check ($conditions)
	{
		return $this->authTarget()::where($conditions)->first();
	}

	protected function throwIfNotFound ($conditions)
	{
		$model = $this->authTarget()::where($conditions)->first();
		if ($model == null)
			throw new ModelNotFoundException();
		else
			return $model;
	}

	protected function throwIfFound ($conditions)
	{
		$model = $this->authTarget()::where($conditions)->first();
		if ($model != null)
			throw new ResourceConflictException();
		else
			return $model;
	}

	protected function verified (\App\Library\Database\Eloquent\AuthEntity $user)
	{
		return $user->account_verified;
	}

	protected function generateAuthToken (Request $request, \App\Library\Database\Eloquent\AuthEntity $user)
	{
		return auth()->guard('api')->attempt(['email' => $user->email, 'password' => $request->password]);
	}

	protected function generateToken (\App\Library\Database\Eloquent\AuthEntity $user)
	{
		return JWTAuth::fromUser($user);
	}

	protected function exists ()
	{
		try {
			$this->requestValid(request(), $this->rulesExists());
			$conditions = $this->conditionsExists(request());
			$user = $this->check($conditions);
			if ($user != null) {
				return $this->success()->status(\Illuminate\Http\Response::HTTP_OK)->message($this->checkSuccess())->send();
			} else {
				return $this->success()->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($this->checkFailed())->send();
			}
		} catch (ValidationException $exception) {
			return $this->failed()->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError())->send();
		} catch (Throwable $exception) {
			return $this->error()->message($exception->getMessage())->send();
		}
	}

	protected function login ()
	{
		$request = request();
		try {
			$this->requestValid($request, $this->rulesLogin());
			$conditions = $this->conditionsLogin($request);
			$seller = $this->throwIfNotFound($conditions);
			if ($this->shouldAllowOnlyActiveUsers() && !$seller->active) {
				return $this->failed()->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($this->deniedAccess())->send();
			}
			$token = $this->guard()->attempt($this->credentials($request));
			if (!$token)
				return $this->failed()->message($this->loginFailed())->status(\Illuminate\Http\Response::HTTP_UNAUTHORIZED)->send();
			else
				return $this->success()->message($this->loginSuccess())->setValue('data', $this->loginPayload($seller, $token))->send();
		} catch (ModelNotFoundException $exception) {
			return $this->failed()->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->send();
		} catch (ValidationException $exception) {
			return $this->failed()->message($exception->getError())->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->send();
		} catch (Throwable $exception) {
			return $this->error()->message($exception->getTraceAsString())->send();
		}
	}

	protected function logout ()
	{
		$request = request();
		try {
			$this->guard()->logout();
			return $this->success()->message($this->logoutSuccess())->send();
		} catch (Exception $exception) {
			return $this->error()->message($exception->getMessage())->send();
		}
	}

	protected function loginPayload (\App\Library\Database\Eloquent\AuthEntity $user, string $token)
	{
		return [
			'name' => $user->name,
			'email' => $user->email,
			'mobile' => $user->mobile,
			'token' => $token,
		];
	}

	protected function registerPayload (\App\Library\Database\Eloquent\AuthEntity $user, string $token)
	{
		return [
			'name' => $user->name,
			'email' => $user->email,
			'mobile' => $user->mobile,
			'token' => $token,
		];
	}

	protected function register ()
	{
		$request = request();
		try {
			$this->requestValid($request, $this->rulesRegister());
			$this->throwIfFound($this->conditionsRegister($request));
			$user = $this->create($request);
			$token = $this->generateToken($user);
			if (!$token)
				return $this->failed()->message($this->registerFailed())->send();
			else
				return $this->success()->message($this->registerSuccess())->status(\Illuminate\Http\Response::HTTP_CREATED)->setValue('data', $this->registerPayload($user, $token))->send();
		} catch (ResourceConflictException $exception) {
			return $this->failed()->status(\Illuminate\Http\Response::HTTP_CONFLICT)->message($this->registerTaken())->send();
		} catch (ValidationException $exception) {
			return $this->failed()->message($exception->getError())->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->send();
		} catch (Throwable $exception) {
			return $this->error()->message($exception->getMessage())->send();
		}
	}

	protected function create ($request)
	{
		return $this->authTarget()::create([
			'name' => $request->name,
			'email' => $request->email,
			'mobile' => $request->mobile,
			'password' => Hash::make($request->password),
		]);
	}

	protected function profile ()
	{
		return new AuthProfileResource($this->guard()->user());
	}

	protected function credentials (Request $request)
	{
		if ($request->exists('mobile')) {
			return [
				'mobile' => $request->mobile,
				'password' => $request->password,
			];
		} else {
			return [
				'email' => $request->email,
				'password' => $request->password,
			];
		}
	}

	public function updateProfile ()
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rulesUpdateProfile());
			$user = $this->guard()->user();
			$user->update($validated);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Profile was updated successfully.')->setValue('payload', $user instanceof Seller ? new AuthProfileResource($user) : new \App\Resources\Auth\Customer\AuthProfileResource($user));
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function updateAvatar ()
	{
		$response = responseApp();
		try {
			$this->requestValid(request(), $this->rulesUpdateAvatar());
			$user = $this->guard()->user();
			Uploads::deleteIfExists($user->avatar);
			$user->update([
				'avatar' => Uploads::access()->putFile($user instanceof Seller ? Directories::SellerAvatars : Directories::CustomerAvatars, \request()->file('avatar')),
			]);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Avatar updated successfully.');
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function updatePassword ()
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(\request(), $this->rulesUpdatePassword());
			$user = $this->guard()->user();
			if (!Hash::check($validated['current'], $user->password())) {
				$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message('Your given current password does not match with the one in you account.');
			} else {
				$user->password(Hash::make($validated['new']));
				$user->save();
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Password updated successfully.');
			}
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}