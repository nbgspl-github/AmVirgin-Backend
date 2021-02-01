<?php

namespace App\Http\Modules\Customer\Resources\Subscription;

class SubscriptionResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'originalPrice' => $this->originalPrice,
			'discountedPrice' => $this->offerPrice,
			'banner' => $this->banner,
			'duration' => $this->duration
		];
	}
}