<?php

namespace App\Resources\Orders\Customer;

use App\Resources\Products\Customer\CatalogListResource;
use App\Resources\Products\Customer\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource{
	public function toArray($request){
		return [
			'product' => new CatalogListResource($this->product),
			'quantity' => $this->quantity(),
			'price' => $this->price(),
			'total' => $this->total(),
			'options' => $this->options(),
		];
	}
}