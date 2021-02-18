<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Video;

class SourceResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'language' => $this->language(),
			'url' => $this->file
		];
	}

	protected function language () : LanguageResource
	{
		return new LanguageResource($this->language);
	}
}