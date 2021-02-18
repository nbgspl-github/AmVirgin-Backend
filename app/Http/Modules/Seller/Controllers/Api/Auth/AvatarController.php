<?php

namespace App\Http\Modules\Seller\Controllers\Api\Auth;

use App\Http\Modules\Seller\Requests\Auth\Profile\Avatar\UpdateRequest;

class AvatarController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
	}

	public function update (UpdateRequest $request) : \Illuminate\Http\JsonResponse
	{
		$this->seller()->update([
			$request->only('avatar')
		]);
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, __('auth.avatar.success'), 'data'
		);
	}
}