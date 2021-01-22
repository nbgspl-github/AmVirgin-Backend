<?php

namespace App\Http\Modules\Seller\Requests\Auth\Password;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'current' => ['bail', 'required', 'string', 'min:4', 'max:64'],
			'new' => ['bail', 'required', 'string', 'min:4', 'max:64', 'different:current'],
			'confirm' => ['bail', 'required', 'string', 'same:new'],
		];
	}
}