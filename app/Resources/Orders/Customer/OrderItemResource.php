<?php

namespace App\Resources\Orders\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource{
	public function toArray($request){
		return [
			'product' => $this->product,
			'quantity' => $this->quantity(),
			'price' => $this->price(),
			'total' => $this->total(),
			'options' => $this->options(),
		];
	}
}