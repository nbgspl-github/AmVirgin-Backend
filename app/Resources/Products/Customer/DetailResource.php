<?php

namespace App\Resources\Products\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailResource extends JsonResource{
	public function toArray($request){
		return [
			'label' => $this->label(),
			'group' => $this->group(),
			'value' => $this->value(),
		];
	}
}