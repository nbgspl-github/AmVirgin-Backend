<?php

namespace App\Resources\Subscriptions\Customer;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource {
	public function toArray($request) {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'originalPrice' => $this->originalPrice,
			'discountedPrice' => $this->offerPrice,
			'banner' => SecuredDisk::existsUrl($this->banner),
			'duration' => $this->duration,
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}