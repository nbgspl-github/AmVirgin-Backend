<?php

namespace App\Http\Modules\Seller\Controllers\Api\Auth;

use App\Http\Modules\Seller\Requests\Auth\Password\UpdateRequest;
use Illuminate\Support\Facades\Hash;

class PasswordController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
	}

	public function update (UpdateRequest $request) : \Illuminate\Http\JsonResponse
	{
		if (Hash::check($request->current, $this->seller()->getAuthPassword())) {
			$this->seller()->update([
				'password' => Hash::make($request->new)
			]);
			return $this->sendPasswordUpdatedResponse();
		}
		return $this->sendPasswordIncorrectResponse();
	}

	protected function sendPasswordIncorrectResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_UNAUTHORIZED, __('auth.password.failed'), 'data'
		);
	}

	protected function sendPasswordUpdatedResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_OK, __('auth.password.success'), 'data'
		);
	}
}