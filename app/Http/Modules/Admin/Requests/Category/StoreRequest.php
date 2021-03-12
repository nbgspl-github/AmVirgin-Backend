<?php

namespace App\Http\Modules\Admin\Requests\Category;

use App\Library\Enums\Categories\Types;
use App\Library\Utils\Extensions\Rule;
use App\Models\Category;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
            'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
            'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
            'parent_id' => ['bail', 'required', 'numeric', Category::exists()],
            'listing' => ['bail', 'required', Rule::in([Category::LISTING_ACTIVE, Category::LISTING_INACTIVE])],
            'type' => ['bail', 'required', Rule::in(Types::Category, Types::SubCategory, Types::Vertical)],
            'icon' => ['bail', 'nullable', 'image'],
            'order' => ['bail', 'required', Rule::minimum(0), Rule::maximum(255)],
            'summary' => ['bail', 'nullable', 'string'],
            'catalog' => ['bail', 'nullable', 'mimes:xls,xlsx', 'max:10240']
        ];
	}
}