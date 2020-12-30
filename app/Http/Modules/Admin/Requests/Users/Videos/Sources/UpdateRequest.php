<?php

namespace App\Http\Modules\Admin\Requests\Users\Videos\Sources;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'title.*' => ['bail', 'required', 'string', 'min:1', 'max:256'],
			'description.*' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
			'duration.*' => ['bail', 'required', 'date_format:H:i:s'],
			'language.*' => ['bail', 'required', 'exists:media-languages,id'],
			'quality.*' => ['bail', 'required', 'exists:media-qualities,id'],
			'video.*' => ['bail', 'required', 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,video/mkv'],
			'subtitle.*' => ['bail', 'nullable', 'min:1', 'max:5120'],
			'source.*' => ['bail', 'nullable'],
		];
	}
}