<?php

namespace App\Http\Modules\Admin\Requests\Genre;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:1', 'max:100', \App\Models\Video\Genre::unique('name')->ignore($this->route('genre'))],
			'poster' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp'],
			'active' => ['bail', 'required', 'boolean'],
		];
	}
}