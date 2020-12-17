<?php

namespace App\Resources\GlobalSearch;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResultResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'title' => $this->title(),
			'description' => $this->description(),
			'poster' => Uploads::existsUrl($this->poster()),
		];
	}
}