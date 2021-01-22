<?php

namespace App\Http\Modules\Customer\Requests\Auth;

class RegisterRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:2', 'max:100'],
			'email' => ['bail', 'required', 'email', 'unique:customers,email'],
			'mobile' => ['bail', 'required', 'digits:10', 'unique:customers,mobile'],
			'password' => ['bail', 'required', 'string', 'min:4', 'max:64'],
			'otp' => ['bail', 'required', 'numeric', 'min:1111', 'max:9999'],
		];
	}
}