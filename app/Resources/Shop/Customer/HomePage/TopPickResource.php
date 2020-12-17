<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class TopPickResource extends JsonResource {
	public function toArray($request) {
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'poster' => Uploads::existsUrl($this->poster),
			'type' => $this->type,
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}