<?php

namespace App\Http\Modules\Admin\Requests\Users\Videos\Subtitle;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'video_language_id' => ['bail', 'required', \App\Library\Utils\Extensions\Rule::existsPrimary(\App\Models\Video\Language::tableName())],
			'file' => ['bail', 'required', 'max:1024']
		];
	}
}