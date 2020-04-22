<?php

namespace App\Resources\Orders\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource{
	public function toArray($request){
		return [
			'product' => null,
			'quantity' => $this->quantity(),
			'',
		];
	}
}