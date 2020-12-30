<?php

namespace App\Http\Modules\Admin\Requests\Users\Videos\Audio;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'video_language_id' => ['bail', 'required', \App\Library\Utils\Extensions\Rule::existsPrimary(\App\Models\Video\Language::tableName())],
			'file' => ['bail', 'required', 'mimes:mp3,aac', 'max:1000000']
		];
	}
}