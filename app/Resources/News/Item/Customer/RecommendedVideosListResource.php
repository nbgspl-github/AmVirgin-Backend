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
			'video' => $this->video,
			'duration' => $this->duration,
			'author' => 'Admin',
			'published' => $this->created_at->format('Y-m-d H:i:s')
		];
	}
}