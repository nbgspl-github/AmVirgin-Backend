<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class TrendingNowVideoResource extends JsonResource{
	public function toArray($request){
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

	public static function withoutWrapping(){
		return true;
	}
}