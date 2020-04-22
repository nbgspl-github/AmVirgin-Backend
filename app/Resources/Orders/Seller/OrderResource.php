<?php

namespace App\Resources\Orders\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'customer' => new OrderCustomerResource($this->customer),
		];
	}
}