<?php

namespace App\Resources\Videos\Customer\WatchLater;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function toArray ($request): array
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
            'trailer' => Uploads::existsUrl($this->trailer),
			'rating' => $this->rating,
			'poster' => Uploads::existsUrl($this->poster),
			'backdrop' => Uploads::existsUrl($this->backdrop),
			'pgRating' => $this->pgRating,
			'subscriptionType' => $this->subscriptionType,
			'hasSeasons' => boolval($this->hasSeasons),
			'price' => $this->price,
		];
	}
}