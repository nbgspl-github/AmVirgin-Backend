<?php

namespace App\Resources\Products\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantItemResource extends AbstractProductResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
			'options' => OptionResource::collection($this->options),
		];
	}
}