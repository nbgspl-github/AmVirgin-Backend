<?php

namespace App\Http\Modules\Admin\Requests\Category;

use App\Library\Enums\Categories\Types;
use App\Library\Utils\Extensions\Rule;
use App\Models\Category;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
			'parent_id' => ['bail', 'required', 'numeric', \App\Models\Category::exists()],
			'listing' => ['bail', 'required', Rule::in([Category::LISTING_INACTIVE, Category::LISTING_ACTIVE])],
			'type' => ['bail', 'required', Rule::in(Types::Category, Types::SubCategory, Types::Vertical)],
			'icon' => ['bail', 'nullable', 'image', 'max:1024'],
			'order' => ['bail', 'required'],
			'catalog' => ['bail', 'nullable', 'mimes:xls,xlsx', 'max:10240']
		];
	}
}