<?php

namespace App\Resources\Shop\Customer\HomePage;

use Illuminate\Http\Resources\Json\JsonResource;

class TrendingNowVideoResource extends JsonResource
{
	public function toArray ($request) : array
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