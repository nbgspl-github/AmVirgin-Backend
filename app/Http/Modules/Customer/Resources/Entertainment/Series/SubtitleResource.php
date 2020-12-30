<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Series;

class SubtitleResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'language' => new LanguageResource($this->language),
			'url' => $this->file
		];
	}
}