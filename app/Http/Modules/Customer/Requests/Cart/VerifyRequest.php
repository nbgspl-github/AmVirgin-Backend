<?php

namespace App\Http\Modules\Customer\Requests\Cart;

class VerifyRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'paymentId' => ['bail', 'required', 'string', 'min:16', 'max:50'],
			'orderId' => ['bail', 'required', 'string', 'min:16', 'max:50'],
			'signature' => ['bail', 'required', 'string', 'max:128']
		];
	}
}