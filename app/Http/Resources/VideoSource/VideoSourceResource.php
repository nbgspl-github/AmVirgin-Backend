<?php

namespace App\Http\Resources\VideoSource;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoSourceResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'episode' => $this->episode,
			'title' => $this->title,
			'description' => $this->description,
			'duration' => $this->duration,
			'season' => $this->season,
			'language' => $this->language()->first()->getName(),
			'quality' => $this->mediaQuality()->first()->getName(),
		];
	}
}