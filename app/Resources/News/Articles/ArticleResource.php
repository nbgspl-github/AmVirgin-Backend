<?php

namespace App\Resources\News\Articles;

class ArticleResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'title' => $this->title,
			'poster' => $this->poster,
			'video' => $this->video,
			'author' => $this->author,
			'content' => $this->content
		];
	}
}