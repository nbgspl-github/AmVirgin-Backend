<?php

namespace App\Resources\Cart;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource {
	public static function withoutWrapping() {
		return true;
	}

	public function toArray($request) {
		return [
			'key' => $this->id,
			'uid' => $this->uniqueId,
			'quantity' => $this->uniqueId,
			'total' => $this->itemTotal,
			'attributes' => jsonDecodeArray($this->options),
		];
	}
}