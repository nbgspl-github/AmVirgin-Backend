<?php

namespace App\Http\Modules\Customer\Requests\Auth;

class SocialLoginRequest extends \App\Http\Modules\Shared\Requests\Auth\ExistsRequest
{
	public function rules () : array
	{
		return [
			'email' => 'bail|required|email|max:255',
			'name' => 'bail|required|string|max:255'
		];
	}
}