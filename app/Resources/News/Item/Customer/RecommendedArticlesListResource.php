<?php

namespace App\Resources\News\Item\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class RecommendedArticlesListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'poster' => $this->poster,
			'author' => $this->author,
			'published' => $this->published_at->format('Y-m-d H:i:s')
		];
	}
}