<?php

namespace App\Resources\News\Articles;

class ArticleCollection extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'poster' => $this->poster,
			'author' => $this->author,
			'published' => $this->published_at
		];
	}
}