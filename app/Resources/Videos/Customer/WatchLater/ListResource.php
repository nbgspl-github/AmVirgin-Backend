<?php

namespace App\Resources\Videos\Customer\WatchLater;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'slug' => $this->slug,
			'type' => $this->type,
			'duration' => $this->duration,
			'released' => $this->released,
			'director' => $this->director,
			'trailer' => SecuredDisk::existsUrl($this->trailer),
			'rating' => $this->rating,
			'poster' => SecuredDisk::existsUrl($this->poster),
			'backdrop' => SecuredDisk::existsUrl($this->backdrop),
			'pgRating' => $this->pgRating,
			'subscriptionType' => $this->subscriptionType,
			'hasSeasons' => boolval($this->hasSeasons),
			'price' => $this->price,
		];
	}
}