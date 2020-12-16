<?php

namespace App\Resources\News\Item\Customer;

use App\Library\Utils\Extensions\Time;
use App\Models\NewsItem;
use App\Models\NewsVideo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ItemResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'content' => $this->content,
			'image' => $this->image,
			'uploadedBy' => $this->uploadedBy,
			'uploadedOn' => Time::mysqlStamp(strtotime($this->created_at)),
			'trending' => TrendingListResource::collection(NewsItem::startQuery()->displayable()->trending()->orderByTrending()->get()),
			'recommended' => [
				'videos' => $this->recommendedVideos(),
				'articles' => $this->recommendedArticles()
			]
		];
	}

	protected function recommendedVideos ()
	{
		return RecommendedVideosListResource::collection(NewsVideo::all());
	}

	protected function recommendedArticles ()
	{
		return RecommendedArticlesListResource::collection(new Collection());
	}
}