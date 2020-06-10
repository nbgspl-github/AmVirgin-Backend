<?php

namespace App\Resources\Products\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogListOptionResource extends JsonResource{
	public function toArray($request){
		return [
			'label' => $this->label(),
			'value' => $this->value,
		];
	}
}