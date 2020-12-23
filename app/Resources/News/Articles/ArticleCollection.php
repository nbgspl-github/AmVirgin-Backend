<?php

namespace App\Resources\News\Articles;

class ArticleCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{
	public function toArray ($request) : array
	{
		return [
			'data' => $this->collection
		];
	}
}