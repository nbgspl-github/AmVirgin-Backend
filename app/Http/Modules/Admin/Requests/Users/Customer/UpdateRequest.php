<?php

namespace App\Http\Modules\Admin\Requests\Users\Customer;

use App\Library\Enums\Common\Tables;
use App\Library\Utils\Extensions\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:4', 'max:50'],
			'mobile' => ['bail', 'required', 'digits:10', \Illuminate\Validation\Rule::unique(Tables::Customers, 'mobile')->ignore($this->route('customer'))],
			'email' => ['bail', 'required', 'email', Rule::unique(Tables::Customers, 'email')->ignore($this->route('customer'))],
			'active' => ['bail', 'required', Rule::in([0, 1])],
			'avatar' => ['bail', 'nullable', 'image', 'max:2048']
		];
	}
}