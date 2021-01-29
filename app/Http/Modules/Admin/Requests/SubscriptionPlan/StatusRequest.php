<?php

namespace App\Http\Modules\Admin\Requests\SubscriptionPlan;

class StatusRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'active' => ['bail', 'required', 'boolean'],
		];
	}
}