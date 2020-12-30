<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Series;

class LanguageResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
		];
	}
}