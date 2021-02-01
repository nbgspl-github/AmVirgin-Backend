<?php

namespace App\Http\Modules\Admin\Requests\TvSeries\Media;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'poster' => 'bail|sometimes|image|max:2048',
			'backdrop' => 'bail|sometimes|image|max:2048',
			'trailer' => 'bail|sometimes|mimes:mp4,mkv|max:102400',
		];
	}
}