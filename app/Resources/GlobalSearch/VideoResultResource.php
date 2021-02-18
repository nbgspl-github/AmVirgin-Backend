<?php

namespace App\Resources\GlobalSearch;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResultResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'poster' => $this->poster,
			'type' => $this->type
		];
	}
}