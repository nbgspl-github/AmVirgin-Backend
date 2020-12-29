<?php

namespace App\Http\Modules\Customer\Requests\Cart;

use App\Library\Utils\Extensions\Rule;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'sessionId' => ['bail', 'required', Rule::exists(\App\Models\Cart\Session::tableName(), 'sessionId')],
			'key' => ['bail', 'required', Rule::existsPrimary(\App\Models\Product::tableName())->whereNull('deleted_at')],
			'quantity' => ['bail', 'required', 'numeric', 'min:1', 'max:100'],
		];
	}
}