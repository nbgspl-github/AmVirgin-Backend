<?php

namespace App\Resources\Shop\Customer\HomePage;

use Illuminate\Http\Resources\Json\JsonResource;

class EntertainmentSliderResource extends JsonResource
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