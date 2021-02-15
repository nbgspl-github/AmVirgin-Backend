<?php

namespace App\Http\Modules\Customer\Resources\Subscription\Rental;

class VideoResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'rentedAt' => $this->valid_from,
			'validFrom' => $this->valid_from,
			'validUntil' => $this->valid_until,
			'video' => $this->video($this->video)
		];
	}

	protected function video ($video) : array
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
			'trailer' => $this->trailer,
			'rating' => $this->rating,
			'poster' => $this->poster,
			'backdrop' => $this->backdrop,
			'pgRating' => $this->pg_rating,
			'subscriptionType' => $this->subscription_type,
			'hasSeasons' => $this->seasons > 0,
			'price' => $this->price,
		];
	}
}