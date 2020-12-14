<?php

namespace App\Resources\Orders\Customer;

use App\Enums\Orders\Status;
use App\Models\SubOrder;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderItemResource
 * @package App\Resources\Orders\Customer
 * @property ?SubOrder $subOrder
 */
class OrderItemResource extends JsonResource
{
	public function toArray ($request): array
	{
		return [
			'key' => $this->id,
			'product' => new CartProductResource($this->product),
			'quantity' => $this->quantity,
			'price' => $this->price,
			'total' => $this->total,
			'options' => $this->options,
			'return' => [
				'allowed' => $this->returnable,
				'period' => $this->returnPeriod,
				'type' => $this->returnType
			],
			'cancel' => [
				'allowed' => $this->subOrder->exists && (!$this->subOrder->status->is(Status::Delivered))
			]
		];
	}
}