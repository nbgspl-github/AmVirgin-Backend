<?php

namespace App\Http\Resources\Videos;

use App\Models\VideoSource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TrendingPicksVideoResource extends JsonResource{
	public static function withoutWrapping(){
		return true;
	}

	public function toArray($request){
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'duration' => $this->duration,
			'released' => $this->released,
			'cast' => $this->cast,
			'director' => $this->director,
			'trailer' => $this->trailer,
			'poster' => Storage::disk('public')->url($this->getPoster()),
			'backdrop' => Storage::disk('public')->url($this->getBackdrop()),
			'genre' => $this->genre->getName(),
			'rating' => $this->averageRating,
			'pgRating' => $this->pgRating,
			'type' => $this->type,
			'subscriptionType' => $this->subcriptionType,
			'hasSeasons' => $this->hasSeasons,
			'price' => $this->price,
			'content' => $this->hasSeasons == true ? TvSeriesResource::collection(VideoSource::where('videoId', $this->id)) : VideoResource::collection(VideoSource::where('videoId', $this->id)),
		];
	}
}