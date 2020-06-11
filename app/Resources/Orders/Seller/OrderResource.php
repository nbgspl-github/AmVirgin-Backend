<?php

namespace App\Resources\Orders\Seller;

use App\Enums\Seller\OrderStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource {
	public function toArray ($request) {
		$status = $this->order;
		$status = $status != null ? $status->status() : OrderStatus::NotAvailable;
		return [
			'key' => $this->id(),
			'customer' => new OrderCustomerResource($this->customer),
			'items' => OrderItemResource::collection($this->items),
			'transitions' => OrderStatus::transitions(new OrderStatus($status)),
		];
	}
}