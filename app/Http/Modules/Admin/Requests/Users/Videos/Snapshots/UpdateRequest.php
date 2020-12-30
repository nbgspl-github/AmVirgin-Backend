<?php

namespace App\Http\Modules\Admin\Requests\Users\Videos\Snapshots;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'image.*' => ['bail', 'required', 'image', 'min:1', 'max:5120'],
		];
	}
}