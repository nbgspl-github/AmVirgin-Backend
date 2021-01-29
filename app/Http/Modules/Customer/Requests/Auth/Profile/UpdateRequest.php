<?php

namespace App\Http\Modules\Customer\Requests\Auth\Profile;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'name' => 'bail|required|string|min:2|max:50',
		];
	}
}