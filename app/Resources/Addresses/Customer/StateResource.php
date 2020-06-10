<?php

namespace App\Resources\Addresses\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
		];
	}
}