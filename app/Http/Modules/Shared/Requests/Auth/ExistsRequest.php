<?php

namespace App\Http\Modules\Shared\Requests\Auth;

use App\Library\Utils\Extensions\Rule;

class ExistsRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'type' => ['bail', 'required', Rule::in([1, 2, 3])],
			'email' => ['bail', 'email', 'required_if:type,1'],
			'mobile' => ['bail', 'digits:10', 'required_if:type,2,3'],
		];
	}
}