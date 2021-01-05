<?php

namespace App\Http\Modules\Admin\Requests\Users\Series\Subtitle;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'video_language_id' => ['bail', 'required', \App\Library\Utils\Extensions\Rule::existsPrimary(\App\Models\Video\Language::tableName())],
			'file' => ['bail', 'required', 'max:1024']
		];
	}

	public function validated () : array
	{
		$validated = parent::validated();
		return array_merge($validated, [
			'video_id' => $this->route('video')
		]);
	}
}