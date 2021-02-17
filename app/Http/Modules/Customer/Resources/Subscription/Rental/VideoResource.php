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
		if (!$video)
			return [];
		return [
			'id' => $video->id,
			'title' => $video->title,
			'description' => $video->description,
			'slug' => $video->slug,
			'type' => $video->type,
			'duration' => $video->duration,
			'released' => $video->released,
			'director' => $video->director,
			'trailer' => $video->trailer,
			'rating' => $video->rating,
			'poster' => $video->poster,
			'backdrop' => $video->backdrop,
			'pgRating' => $video->pg_rating,
			'subscriptionType' => $video->subscription_type,
			'hasSeasons' => $video->seasons > 0,
			'price' => $video->price,
		];
	}
}