<?php

namespace App\Http\Requests\Admin\Customers;

use App\Library\Utils\Extensions\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:4', 'max:50'],
			'mobile' => ['bail', 'required', 'digits:10', Rule::unique('customers', 'mobile')],
			'email' => ['bail', 'required', 'email', Rule::unique('customers', 'email')],
			'password' => ['bail', 'required', 'string', 'min:4', 'max:128'],
			'active' => ['bail', 'required', Rule::in([0, 1])],
			'avatar' => ['bail', 'nullable', 'image', 'max:2048']
		];
	}
}