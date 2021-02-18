<?php

namespace App\Http\Modules\Admin\Requests\SubscriptionPlan;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:1', 'max:100', \App\Models\SubscriptionPlan::unique('name')],
			'description' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
			'originalPrice' => ['bail', 'required', 'numeric', 'min:0.00', 'max:100000.00'],
			'offerPrice' => ['bail', 'required', 'numeric', 'min:0.00', 'max:100000.00'],
			'banner' => ['bail', 'required', 'image'],
			'duration' => ['bail', 'required', 'numeric', 'min:0', 'max:1200'],
			'active' => ['bail', 'required', 'boolean'],
		];
	}
}