<?php

namespace App\Http\Modules\Seller\Requests\Dashboard;

class IndexRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'days' => 'bail|nullable|numeric|min:1|max:50000'
		];
	}
}