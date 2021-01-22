<?php

namespace App\Http\Modules\Customer\Requests\Auth\Profile\Avatar;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'avatar' => ['bail', 'required', 'image', 'max:2048']
		];
	}
}