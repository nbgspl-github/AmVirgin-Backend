<?php

namespace App\Resources\Sliders\Shop;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopSliderResource extends JsonResource {
	public static function withoutWrapping() {
		return true;
	}

	public function toArray($request) {
		return [
			'title' => $this->title(),
			'description' => $this->description,
			'poster' => Uploads::existsUrl($this->poster),
			'target' => $this->target,
			'stars' => $this->stars,
		];
	}
}