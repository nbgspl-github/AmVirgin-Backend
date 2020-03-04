<?php

namespace App\Resources\Search\Customer\Entertainment;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource {
	public function toArray($request) {
		return [
			'id' => $this->id,
			'title' => $this->name,
			'description' => $this->description,
			'poster' => SecuredDisk::existsUrl($this->poster),
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}