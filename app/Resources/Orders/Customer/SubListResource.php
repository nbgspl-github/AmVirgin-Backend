<?php

namespace App\Resources\Orders\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class SubListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'details' => [
				'quantity' => $this->quantity,
				'tax' => $this->tax,
				'subTotal' => $this->subTotal,
				'total' => $this->total,
				'status' => $this->status,
			],
			'items' => ItemResource::collection($this->items),
			'cancel' => [
				'allowed' => $this->cancellable(),
			]
		];
	}

	/**
	 * The sub order can only be cancelled until the order has been delivered.
	 * Once the order has been delivered, it can only be returned at an individual item level.
	 * @return bool
	 */
	protected function cancellable () : bool
	{
		return empty($this->fulfilled_at);
	}
}