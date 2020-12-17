<?php

namespace App\Http\Resources\Videos;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
	public static function withoutWrapping ()
	{
		return true;
	}

	public function toArray ($request)
	{
		return [
			'slug' => $this->slug,
			'title' => $this->title,
			'description' => $this->description,
			'duration' => $this->duration,
			'released' => $this->released,
			'cast' => $this->cast,
			'director' => $this->director,
			'trailer' => Uploads::existsUrl($this->trailer),
			'poster' => Uploads::existsUrl($this->getPoster()),
			'backdrop' => Uploads::existsUrl($this->getBackdrop()),
			'genre' => $this->genre->getName(),
			'rating' => $this->rating,
			'pgRating' => $this->pgRating,
			'type' => $this->type,
			'subscriptionType' => $this->subscriptionType,
			'hasSeasons' => boolval($this->hasSeasons),
			'price' => $this->price,
		];
	}
}