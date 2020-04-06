<?php

namespace App\Resources\Products\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogListingOptionResource extends JsonResource{
	public function toArray($request){
		return [
			'label' => $this->label(),
			'value' => $this->value,
		];
	}
}