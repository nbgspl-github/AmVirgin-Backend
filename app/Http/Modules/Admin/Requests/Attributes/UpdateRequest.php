<?php

namespace App\Http\Modules\Admin\Requests\Attributes;

use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Str;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
			'description' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
			'group' => ['bail', 'required', 'string', 'min:1', 'max:50'],
			'values' => ['bail', 'required_with:predefined,on',
				function ($attribute, $value, $fail) {
					if (request()->has('predefined')) {
						$values = Str::split(';', $value);
						if (Arrays::length($values) < 1) {
							$fail('Minimum 1 value is required when predefined values are given.');
						}
					}
				},
			],
		];
	}
}