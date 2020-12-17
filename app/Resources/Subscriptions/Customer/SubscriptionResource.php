<?php

namespace App\Resources\Subscriptions\Customer;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource {
	public function toArray($request) {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'originalPrice' => $this->originalPrice,
			'discountedPrice' => $this->offerPrice,
			'banner' => Uploads::existsUrl($this->banner),
			'duration' => $this->duration,
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}