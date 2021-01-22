<?php

namespace App\Http\Modules\Customer\Requests\Brand;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'status' => ['bail', 'required', \App\Library\Utils\Extensions\Rule::in(\App\Library\Enums\Brands\Status::getValues())]
		];
	}
}