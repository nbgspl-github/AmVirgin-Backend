<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Resources\Products\Customer\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TrendingDealsResource extends JsonResource {
	public function toArray($request) {
		return [
			'key' => $this->id,
			'name' => $this->name,
			'images' => ImageResource::collection($this->images),
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}