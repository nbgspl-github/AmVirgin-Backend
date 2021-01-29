<?php

namespace App\Resources\Subscriptions\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'originalPrice' => $this->originalPrice,
			'discountedPrice' => $this->offerPrice,
			'banner' => $this->banner,
			'duration' => $this->duration,
		];
	}
}