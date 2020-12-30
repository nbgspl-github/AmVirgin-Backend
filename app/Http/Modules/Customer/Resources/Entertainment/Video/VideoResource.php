<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Video;

class VideoResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'title' => $this->title,
			'description' => $this->description,
			'duration' => $this->duration,
			'released' => $this->released,
			'cast' => $this->cast,
			'director' => $this->director,
			'trailer' => $this->trailer,
			'poster' => $this->poster,
			'backdrop' => $this->backdrop,
			'genre' => $this->genre->name,
			'rating' => $this->rating,
			'pgRating' => $this->pg_rating,
			'type' => $this->type,
			'subscriptionType' => $this->subscription_type,
			'hasSeasons' => $this->seasons > 0,
			'price' => $this->price,
			'sources' => [
				'video' => $this->video(),
				'audio' => $this->audio(),
				'subtitle' => $this->subtitle()
			]
		];
	}

	public function video () : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
		return SourceResource::collection($this->sources);
	}

	public function audio () : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
		return AudioResource::collection($this->audios);
	}

	public function subtitle () : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
		return SubtitleResource::collection($this->subtitles);
	}
}