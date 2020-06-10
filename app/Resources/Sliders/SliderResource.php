<?php

namespace App\Resources\Sliders;

use App\Classes\Str;
use App\Models\PageSection;
use App\Models\Slider;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource{
	public function toArray($request){
		return [
			'title' => $this->title(),
			'description' => $this->description(),
			'banner' => $this->banner(),
			'rating' => $this->rating(),
			'type' => $this->type(),
			'target' => $this->getTarget(),
		];
	}

	public function getTarget(){
		if (Str::equals($this->type(), Slider::TargetType['ExternalLink'])) {
			return $this->target();
		}
		else if (Str::equals($this->type(), Slider::TargetType['ProductKey'])) {
			return $this->product;
		}
		else {
			return $this->video;
		}
	}
}