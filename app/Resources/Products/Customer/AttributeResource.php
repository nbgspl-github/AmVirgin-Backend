<?php

namespace App\Resources\Products\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource{
	public function toArray($request){
		return [
			'label' => $this->name(),
			'value' => $this->values(),
		];
	}
}