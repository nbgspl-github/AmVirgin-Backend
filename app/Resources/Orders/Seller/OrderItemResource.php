<?php

namespace App\Resources\Orders\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource{
	public function toArray($request){
		return [
			'quantity' => $this->quantity(),
			'product' => new OrderProductResource($this->product),
		];
	}
}