<?php

namespace App\Resources\Search\Customer\Entertainment;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource {
	public function toArray($request) {
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'poster' => Uploads::existsUrl($this->poster),
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}