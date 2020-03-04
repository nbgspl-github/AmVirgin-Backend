<?php

namespace App\Resources\Search\Customer\Entertainment;

use App\Resources\Products\Customer\ProductImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource {
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