<?php

namespace App\Resources\Orders\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource{
	public function toArray($request){
		return [
			'address' => $this->address,
			'orderNumber' => $this->orderNumber(),
			'quantity' => $this->quantity(),
			'subTotal' => $this->subTotal(),
			'tax' => $this->tax(),
			'total' => $this->total(),
			'paymentMode' => $this->paymentMode(),
			'status' => $this->status(),
			'items' => OrderItemResource::collection($this->items),
		];
	}
}