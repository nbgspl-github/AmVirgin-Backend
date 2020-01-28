<?php

namespace App\Http\Resources\Videos;

use App\Http\Resources\VideoSource\VideoSourceResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VideoResource extends JsonResource{
	public static function withoutWrapping(){
		return true;
	}

	public function toArray($request){
		return [
			'slug' => $this->slug,
			'title' => $this->title,
			'description' => $this->description,
			'duration' => $this->duration,
			'released' => $this->released,
			'cast' => $this->cast,
			'director' => $this->director,
			'trailer' => Storage::disk('secured')->url($this->trailer),
			'poster' => Storage::disk('secured')->url($this->getPoster()),
			'backdrop' => Storage::disk('secured')->url($this->getBackdrop()),
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