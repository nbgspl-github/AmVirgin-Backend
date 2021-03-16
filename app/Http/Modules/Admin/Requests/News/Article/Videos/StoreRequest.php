<?php

namespace App\Http\Modules\Admin\Requests\News\Article\Videos;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'title' => 'bail|required|string|min:2|max:255',
			'thumbnail' => 'bail|required|image|max:2048',
			'category_id' => ['bail', 'required', \App\Models\News\Category::exists()],
			'author' => 'bail|required|string|min:2|max:50',
			'video' => 'bail|required|mimes:mp4,mkv|max:512000'
		];
	}

	public function validated () : array
	{
		$validated = parent::validated();
		return array_merge($validated, [
            'published' => request()->has('publish'),
            'published_at' => request()->has('publish') ? now()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT) : null,
            'type' => 'video'
        ]);
	}
}