<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Resources\Products\Customer\ProductImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EntertainmentProductResource extends JsonResource {
	public function toArray($request) {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'price' => $this->originalPrice,
			'images' => ProductImageResource::collection($this->images),
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}