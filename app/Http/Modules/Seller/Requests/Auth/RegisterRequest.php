<?php

namespace App\Http\Modules\Seller\Requests\Auth;

class RegisterRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:2', 'max:256'],
			'email' => ['bail', 'required', 'email', 'unique:sellers,email'],
			'mobile' => ['bail', 'required', 'digits:10', 'unique:sellers,mobile'],
			'password' => ['bail', 'required', 'string', 'min:4', 'max:64'],
			'otp' => ['bail', 'required', 'numeric', 'min:1111', 'max:9999'],
		];
	}
}