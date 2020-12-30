<?php

namespace App\Http\Resources\Videos;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
	public function toArray ($request) : array
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
			'poster' => Uploads::existsUrl($this->poster),
			'backdrop' => Uploads::existsUrl($this->backdrop),
			'genre' => $this->genre->name,
			'rating' => $this->rating,
			'pgRating' => $this->pg_rating,
			'type' => $this->type,
			'subscriptionType' => $this->subscription_type,
			'hasSeasons' => $this->seasons > 0,
			'price' => $this->price,
		];
	}
}