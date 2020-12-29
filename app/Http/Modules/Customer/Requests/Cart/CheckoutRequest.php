<?php

namespace App\Http\Modules\Customer\Requests\Cart;

use App\Library\Enums\Orders\Payments\Methods;
use App\Library\Utils\Extensions\Rule;

class CheckoutRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'sessionId' => ['bail', 'required', Rule::exists(\App\Models\Cart\Session::tableName(), 'sessionId')],
			'paymentMode' => ['bail', 'required', Rule::in(Methods::getValues())],
		];
	}
}