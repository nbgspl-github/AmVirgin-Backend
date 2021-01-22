<?php

namespace App\Http\Modules\Customer\Controllers\Api\Auth;

use App\Http\Modules\Customer\Requests\Auth\SocialLoginRequest;
use App\Models\Auth\Customer;
use Illuminate\Support\Facades\Hash;

class SocialLoginController extends \App\Http\Modules\Shared\Controllers\Api\AuthController
{
	public function __construct ()
	{
		parent::__construct(app(Customer::class));
	}

	protected function login (SocialLoginRequest $request) : \Illuminate\Http\JsonResponse
	{
		$customer = Customer::findBy($request->only('email'));
		if ($customer == null) {
			$validated = $request->validated();
			$validated['password'] = Hash::make('12345678');
			$customer = Customer::query()->create($validated);
		}
		return $this->sendLoginSuccessResponse($customer);
	}

	protected function sendLoginSuccessResponse (Customer $customer) : \Illuminate\Http\JsonResponse
	{
		$token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($customer);
		return responseApp()->prepare([
			(new \App\Resources\Auth\Customer\AuthProfileResource($customer))->token($token), \Illuminate\Http\Response::HTTP_OK, __('auth.login.success'), 'data'
		]);
	}
}