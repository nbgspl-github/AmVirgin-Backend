<?php

namespace App\Http\Modules\Seller\Requests\Auth;

use App\Library\Utils\Extensions\Rule;

class LoginRequest extends \App\Http\Modules\Shared\Requests\Auth\ExistsRequest
{
	public function rules () : array
	{
		return [
			'type' => ['bail', 'required', Rule::in([1, 2, 3])],
			'email' => ['bail', 'required_if:type,1', 'nullable'],
			'mobile' => ['bail', 'required_if:type,2,3', 'nullable', 'digits:10'],
			'password' => ['bail', 'required_if:type,1,2', 'string', 'min:4', 'max:64'],
			'otp' => ['bail', 'required_if:type,3', 'numeric', 'min:1111', 'max:9999'],
		];
	}
}