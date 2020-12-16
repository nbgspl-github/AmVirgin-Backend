<?php

namespace App\Resources\Orders\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'details' => [
				'number' => $this->orderNumber,
				'quantity' => $this->quantity,
				'paymentMode' => $this->paymentMode,
				'tax' => $this->tax,
				'subTotal' => $this->subTotal,
				'total' => $this->total,
				'status' => $this->status,
			],
			'subOrders' => SubListResource::collection($this->subOrders)
		];
	}
}