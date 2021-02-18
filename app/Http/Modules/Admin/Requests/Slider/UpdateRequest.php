<?php

namespace App\Http\Modules\Admin\Requests\Slider;

use App\Library\Utils\Extensions\Rule;
use App\Models\Slider;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
			'description' => ['bail', 'required', 'string', 'min:1', 'max:2048'],
			'banner' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp'],
			'type' => ['bail', 'required', Rule::in([Slider::TargetType['ExternalLink'], Slider::TargetType['VideoKey']])],
			'targetLink' => ['bail', 'required_if:type,external-link', 'url'],
			'targetKey' => ['bail', 'required_if:type,video-key', \App\Models\Video\Video::exists()->where('pending', false)],
			'rating' => ['bail', 'required', 'numeric', 'min:0', 'max:5'],
			'active' => ['bail', 'required', 'boolean'],
		];
	}

	public function validated () : array
	{
		$validated = parent::validated();
		if ($validated['type'] == Slider::TargetType['ExternalLink']) {
			$validated['target'] = $validated['targetLink'];
		} else {
			$validated['target'] = $validated['targetKey'];
		}
		return array_merge($validated, []);
	}
}