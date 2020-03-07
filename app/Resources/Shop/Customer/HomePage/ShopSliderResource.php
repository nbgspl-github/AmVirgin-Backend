<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopSliderResource extends JsonResource {
	public function toArray($request) {
		return [
			'title' => $this->title,
			'description' => $this->description,
			'banner' => SecuredDisk::existsUrl($this->banner),
			'target' => $this->target,
			'rating' => $this->rating,
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}