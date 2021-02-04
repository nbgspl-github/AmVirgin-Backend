<?php

namespace App\Http\Modules\Customer\Requests\Subscription;

class SubmitRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'paymentId' => ['bail', 'required_unless:paymentMode,cash-on-delivery', 'string', 'min:16', 'max:50'],
			'orderId' => ['bail', 'required', 'string', 'min:16', 'max:50'],
			'signature' => ['bail', 'required_unless:paymentMode,cash-on-delivery', 'string', 'max:128'],
			'transactionId' => ['bail', 'required', \App\Models\Order\Transaction::exists()]
		];
	}
}