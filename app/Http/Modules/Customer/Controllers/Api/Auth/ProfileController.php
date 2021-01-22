<?php

namespace App\Http\Modules\Customer\Controllers\Api\Auth;

use App\Http\Modules\Customer\Requests\Auth\Profile\UpdateRequest;
use App\Resources\Auth\Customer\AuthProfileResource;

class ProfileController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER);
	}

	public function show () : AuthProfileResource
	{
		return new AuthProfileResource($this->customer());
	}

	public function update (UpdateRequest $request) : \Illuminate\Http\JsonResponse
	{
		$this->customer()->update($request->validated());
		return responseApp()->prepare(
			new AuthProfileResource($this->customer()), \Illuminate\Http\Response::HTTP_OK, __('auth.profile.success')
		);
	}
}