<?php

namespace App\Http\Resources\Videos;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TvSeriesResource extends JsonResource{
	public function toArray($request){
		return [
			'id' => $this->id,
			'seasonId' => $this->season,
			'description' => $this->description,
			'poster' => Storage::disk('public')->url($this->poster),
			'backdrop' => Storage::disk('public')->url($this->backdrop),
		];
	}
}