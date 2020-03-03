<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class TrendingNowResource extends JsonResource {
	public function toArray($request) {
		return [
			'name' => $this->name,
			'description' => $this->description,
			'poster' => SecuredDisk::existsUrl($this->poster),
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}