<?php

namespace App\Http\Modules\Seller\Requests\Payment;

class IndexRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'key' => ['bail', 'nullable', 'integer'],
			'start' => ['bail', 'nullable', 'date_format:Y-m-d'],
			'end' => ['bail', 'nullable', 'required_with:start', 'date_format:Y-m-d', 'after:start']
		];
	}
}