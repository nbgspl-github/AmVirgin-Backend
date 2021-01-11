<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Video;

class VideoResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	protected $marked = false;

	public function __construct ($resource, bool $marked = false)
	{
		parent::__construct($resource);
		$this->marked = $marked;
	}

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
			'price' => $this->price,
			'seasons' => 0,
			'marked' => $this->marked,
			'sources' => [
				'video' => $this->video(),
				'audio' => $this->audio(),
				'subtitle' => $this->subtitle()
			]
		];
	}

	public function languages ()
	{

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