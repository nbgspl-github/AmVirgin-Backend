<?php

namespace App\Http\Modules\Customer\Requests\Shop\Product\Ratings;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'review' => 'bail|nullable|string|max:2048',
			'stars' => 'bail|required|numeric|min:0.11|max:5.00',
			'image.*' => ['bail', 'nullable', 'image', 'max:2048']
		];
	}

	public function validated () : array
	{
		$validated = parent::validated();
		return array_merge($validated, [
			'customer_id' => $this->user('customer-api')->id()
		]);
	}
}