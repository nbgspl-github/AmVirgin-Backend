<?php

namespace App\Http\Modules\Customer\Requests\Cart;

use App\Library\Enums\Orders\Payments\Methods;
use App\Library\Utils\Extensions\Rule;

class SubmitRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'sessionId' => ['bail', 'required', Rule::exists(\App\Models\Cart\Session::tableName(), 'sessionId')],
			'addressId' => ['bail', 'required', Rule::exists(\App\Models\Address\Address::tableName(), 'id')],
			'billingAddressId' => ['bail', 'sometimes', Rule::exists(\App\Models\Address\Address::tableName(), 'id')],
			'paymentMode' => ['bail', 'required', Rule::in(Methods::getValues())],
			'paymentId' => ['bail', 'required_unless:paymentMode,cash-on-delivery', 'string', 'min:16', 'max:50'],
			'orderId' => ['bail', 'required', 'string', 'min:16', 'max:50'],
			'signature' => ['bail', 'required_unless:paymentMode,cash-on-delivery', 'string', 'max:128'],
		];
	}
}