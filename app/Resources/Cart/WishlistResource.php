<?php

namespace App\Resources\Cart;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource {
	public static function withoutWrapping() {
		return true;
	}

	public function toArray($request) {
		return [

		];
	}
}