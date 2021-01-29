<?php

namespace App\Http\Modules\Admin\Requests\News\Category;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:2', 'max:256', \App\Models\News\Category::unique('name')->ignore($this->route('category'))],
			'description' => 'bail|required|string|min:2|max:2048',
			'order' => 'bail|required|numeric|min:0|max:127'
		];
	}
}