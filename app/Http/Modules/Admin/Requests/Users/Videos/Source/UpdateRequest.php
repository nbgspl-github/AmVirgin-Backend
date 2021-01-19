<?php

namespace App\Http\Modules\Admin\Requests\Users\Videos\Source;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'file' => ['bail', 'required', 'mimes:mp4,mkv', 'max:10000000']
		];
	}
}