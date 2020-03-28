<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TrendingNowResource extends JsonResource {
	public function toArray($request) {
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
			'poster' => Storage::disk('secured')->url($this->poster),
			'pgRating' => $this->pgRating,
			'subscriptionType' => $this->subscriptionType,
			'hasSeasons' => boolval($this->hasSeasons),
			'price' => $this->price,
		];
	}

	public static function withoutWrapping() {
		return true;
	}
}