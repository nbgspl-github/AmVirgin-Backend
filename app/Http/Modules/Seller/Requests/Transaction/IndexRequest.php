<?php

namespace App\Http\Modules\Seller\Requests\Transaction;

class IndexRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'referenceId' => ['bail', 'nullable', 'string', 'min:8', 'max:255'],
			'start' => ['bail', 'nullable', 'date_format:Y-m-d'],
			'end' => ['bail', 'nullable', 'required_with:start', 'date_format:Y-m-d', 'after:start']
		];
	}
}