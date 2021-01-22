<?php

namespace App\Http\Modules\Admin\Requests\Category;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
			'parent_id' => ['bail', 'required', 'numeric', \App\Models\Category::exists()],
			'listing' => ['bail', 'required', Rule::in([Category::ListingStatus['Active'], Category::ListingStatus['Inactive']])],
			'type' => ['bail', 'required', Rule::in(Category::Types['Category'], Category::Types['SubCategory'], Category::Types['Vertical'])],
			'icon' => ['bail', 'nullable', 'image', 'max:1024'],
			'order' => ['bail', 'required'],
			'summary' => ['bail', 'nullable', 'string'],
			'catalog' => ['bail', 'nullable', 'mimes:xls,xlsx', 'max:10240']
		];
	}
}