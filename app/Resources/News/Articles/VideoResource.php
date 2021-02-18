<?php

namespace App\Resources\News\Articles;

class VideoResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'title' => $this->title,
			'thumbnail' => $this->thumbnail,
			'video' => $this->video,
			'duration' => $this->duration,
			'views' => $this->views,
			'shares' => $this->shares,
			'published' => $this->created_at->format('Y-m-d H:i:s'),
		];
	}
}