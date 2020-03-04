<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Resources\Products\Customer\ProductImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TrendingDealsResource extends JsonResource {
	public function toArray($request) {
		return [
			'key' => $this->id,
			'name' => $this->name,
			'images' => ProductImageResource::collection($this->images),
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}