<?php

namespace App\Http\Resources\Videos;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VideoResource extends JsonResource{
	public function toArray($request){
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'movieDBId' => $this->movieDBId,
			'imdbId' => $this->imdbId,
			'releaseDate' => $this->releaseDate,
			'averageRating' => $this->averageRating,
			'votes' => $this->votes,
			'popularity' => $this->popularity,
			'genreId' => $this->genreId,
			'poster' => Storage::disk('public')->url($this->poster),
			'backdrop' => Storage::disk('public')->url($this->backdrop),
		];
	}
}