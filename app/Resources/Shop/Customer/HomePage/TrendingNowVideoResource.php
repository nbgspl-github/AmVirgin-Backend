<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class TrendingNowVideoResource extends JsonResource {
	public function toArray($request) {
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'poster' => SecuredDisk::existsUrl($this->poster),
			'type' => $this->type,
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}