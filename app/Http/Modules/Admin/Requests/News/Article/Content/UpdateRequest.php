<?php

namespace App\Http\Modules\Admin\Requests\News\Article\Content;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'title' => 'bail|required|string|min:2|max:255',
			'category_id' => ['bail', 'required', \App\Models\News\Category::exists()],
			'author' => 'bail|required|string|min:2|max:50',
			'estimated_read' => 'bail|required|numeric|min:1|max:120',
			'content' => 'bail|required|string|min:2|max:10000000'
		];
	}

	public function validated () : array
	{
		$validated = parent::validated();
		return array_merge($validated, [
            'published' => request()->has('publish'),
            'published_at' => request()->has('publish') ? now()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT) : null,
            'type' => 'article'
        ]);
	}
}