<?php

namespace App\Http\Modules\Customer\Requests\Shop\Catalog;

use App\Library\Utils\Extensions\Rule;

class IndexRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'sortBy' => ['bail', 'nullable', Rule::in(collect(config('sorts.shop'))->keys()->toArray())],
			'page' => ['bail', 'nullable', 'numeric', 'min:1', 'max:10000'],
		];
	}
}