<?php

namespace App\Http\Modules\Customer\Controllers\Api\Auth;

use App\Http\Modules\Customer\Requests\Auth\Password\UpdateRequest;
use Illuminate\Support\Facades\Hash;

class PasswordController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER);
	}

	public function update (UpdateRequest $request) : \Illuminate\Http\JsonResponse
	{
		if (Hash::check($request->current, $this->customer()->getAuthPassword())) {
			$this->customer()->update([
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