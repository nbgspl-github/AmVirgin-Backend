<?php

namespace App\Resources\Products\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
		];
	}
}