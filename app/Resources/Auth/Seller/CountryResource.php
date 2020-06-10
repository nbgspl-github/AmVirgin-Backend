<?php

namespace App\Resources\Auth\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
		];
	}
}