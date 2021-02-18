<?php

namespace App\Http\Modules\Customer\Requests\Auth\Password;

class ResetRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'token' => 'required',
			'email' => 'required_without:mobile|email',
			'mobile' => 'required_without:email|digits:10',
			'password' => 'required|confirmed|min:8',
		];
	}
}