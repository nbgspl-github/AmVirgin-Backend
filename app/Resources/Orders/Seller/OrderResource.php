<?php

namespace App\Resources\Orders\Seller;

use App\Classes\Arrays;
use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource {
	public function toArray ($request) {
		return [
			'key' => $this->id(),
			'customer' => new OrderCustomerResource($this->customer),
			'items' => OrderItemResource::collection($this->items),
			'transitions' => Arrays::values(Order::$status),
		];
	}
}