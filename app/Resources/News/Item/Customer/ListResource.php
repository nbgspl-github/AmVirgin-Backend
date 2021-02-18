<?php

namespace App\Resources\News\Item\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return $this->type->is(\App\Library\Enums\News\Article\Types::Article)
			? $this->article()
			: $this->video();
	}

	protected function article () : array
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'thumbnail' => $this->thumbnail,
			'author' => $this->author,
			'published' => $this->published_at->format('Y-m-d H:i:s'),
			'views' => $this->views,
			'estimatedRead' => $this->estimated_read
		];
	}

	protected function video () : array
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'thumbnail' => $this->thumbnail,
			'published' => $this->created_at->format('Y-m-d H:i:s'),
			'type' => $this->type
		];
	}
}