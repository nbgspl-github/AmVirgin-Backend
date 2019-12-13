<?php

namespace App\Http\Resources\Sliders;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderCollection extends JsonResource{
	public function toArray($request){
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'target' => $this->target,
		];
	}
}