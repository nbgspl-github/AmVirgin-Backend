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
			'author' => $this->author
		];
	}
}