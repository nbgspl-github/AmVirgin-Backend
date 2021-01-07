<?php

namespace App\Http\Modules\Customer\Requests\Auth\Password;

class InitiateReset extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'email' => ['bail', 'required', \App\Library\Utils\Extensions\Rule::exists(\App\Models\Auth\Customer::tableName(), 'email')]
		];
	}
}