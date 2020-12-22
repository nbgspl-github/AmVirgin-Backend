<?php

namespace App\Resources\News\Item\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class RecommendedVideosListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'thumbnail' => $this->thumbnail,
			'video' => $this->video
		];
	}
}