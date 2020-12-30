<?php

namespace App\Http\Modules\Customer\Resources\Entertainment;

class SliderResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'title' => $this->title,
			'description' => $this->description,
			'banner' => $this->banner,
			'type' => $this->type,
			'target' => $this->target,
			'rating' => $this->rating,
		];
	}
}