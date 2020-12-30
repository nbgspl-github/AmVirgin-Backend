<?php

namespace App\Http\Modules\Admin\Requests\Users\Videos\Media;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'poster' => ['bail', 'nullable', 'image', 'min:1', 'max:5096'],
			'backdrop' => ['bail', 'nullable', 'image', 'min:1', 'max:5096'],
			'trailer' => ['bail', 'nullable', 'mimetypes:video/avi,video/mpeg,video/mp4', 'min:1', 'max:131072'],
		];
	}
}