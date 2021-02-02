<?php

namespace App\Http\Modules\Customer\Controllers\Api\Auth;

use App\Http\Modules\Customer\Requests\Auth\Profile\Avatar\UpdateRequest;

class AvatarController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER);
	}

	public function update (UpdateRequest $request) : \Illuminate\Http\JsonResponse
	{
		$this->customer()->update(
			$request->only('avatar')
		);
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, __('auth.avatar.success'), 'data'
		);
	}
}