<?php

namespace App\Http\Modules\Seller\Controllers\Api\Auth;

use App\Http\Modules\Seller\Requests\Auth\Profile\UpdateRequest;
use App\Resources\Auth\Seller\AuthProfileResource;

class ProfileController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
	}

	public function show () : AuthProfileResource
	{
		return new AuthProfileResource($this->seller());
	}

	public function update (UpdateRequest $request) : \Illuminate\Http\JsonResponse
	{
		$this->seller()->update($request->validated());
		return responseApp()->prepare(
			new AuthProfileResource($this->seller()), \Illuminate\Http\Response::HTTP_OK, __('auth.profile.success')
		);
	}
}