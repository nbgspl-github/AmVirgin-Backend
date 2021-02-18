<?php

namespace App\Http\Modules\Customer\Resources\Entertainment;

class VideoSectionResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'poster' => $this->poster,
			'type' => $this->type,
			'subscriptionType' => $this->subscription_type,
			'price' => $this->price
		];
	}
}