<?php

namespace App\Resources\Orders\Seller;

use App\Models\SellerOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'customer' => new OrderCustomerResource($this->customer),
			'items' => OrderItemResource::collection($this->items),
			'transitions' => SellerOrder::AllowedStatuses[$this->status()],
		];
	}
}