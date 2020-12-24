<?php

namespace App\Resources\News\Item\Customer;

use App\Models\NewsItem;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'content' => $this->content,
			'image' => $this->image,
			'author' => $this->author,
			'published' => $this->created_at->format('Y-m-d H:i:s'),
			'trending' => TrendingListResource::collection(NewsItem::startQuery()->displayable()->trending()->orderByTrending()->get()),
			'recommended' => [
				'videos' => $this->recommendedVideos(),
				'articles' => $this->recommendedArticles()
			]
		];
	}

	protected function recommendedVideos () : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
		return RecommendedVideosListResource::collection(\App\Models\News\Video::all());
	}

	protected function recommendedArticles () : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
		return RecommendedArticlesListResource::collection(\App\Models\News\Article::all());
	}
}