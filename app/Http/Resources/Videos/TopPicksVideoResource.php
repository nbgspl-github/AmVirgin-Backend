<?php

namespace App\Http\Resources\Videos;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TopPicksVideoResource extends JsonResource{
	public static function withoutWrapping(){
		return true;
	}

	public function toArray($request){
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'releaseDate' => $this->released,
			'rating' => $this->rating,
			'genre' => $this->genre->getName(),
			'language' => $this->language->getName(),
			'poster' => Storage::disk('public')->url($this->getPoster()),
			'backdrop' => Storage::disk('public')->url($this->getBackdrop()),
		];
	}
}