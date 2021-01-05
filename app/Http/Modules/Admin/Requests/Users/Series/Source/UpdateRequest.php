<?php

namespace App\Http\Modules\Admin\Requests\Users\Series\Source;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
			'description' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
			'season' => ['bail', 'required'],
			'episode' => ['bail', 'required'],
			'file' => ['bail', 'required', 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,video/mkv'],
		];
	}
}