<?php

namespace App\Http\Resources\Sliders;

use Illuminate\Http\Resources\Json\JsonResource;

class HomepageSliderResource extends JsonResource{
	public static function withoutWrapping(){
		return true;
	}

	public function toArray($request){
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
//			'poster'=>
		];
	}
}