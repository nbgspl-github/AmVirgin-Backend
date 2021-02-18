<?php

namespace App\Http\Modules\Admin\Requests\Users\Seller;

use App\Library\Utils\Extensions\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:4', 'max:50'],
			'mobile' => ['bail', 'required', 'digits:10', \App\Models\Auth\Seller::unique('mobile')->ignore($this->route('seller'))],
			'email' => ['bail', 'required', 'email', \App\Models\Auth\Seller::unique('email')->ignore($this->route('seller'))],
			'active' => ['bail', 'required', Rule::in([0, 1])],
			'avatar' => ['bail', 'nullable', 'image', 'max:2048']
		];
	}
}