<?php

namespace App\Resources\Orders\Seller;

use App\Library\Enums\Orders\Status;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderResource
 * @package App\Resources\Orders\Seller
 * @property Status $status
 */
class OrderResource extends JsonResource
{
	public function toArray ($request): array
	{
		return [
			'key' => $this->id,
			'status' => $this->status,
			'customer' => new OrderCustomerResource($this->customer),
			'items' => OrderItemResource::collection($this->items),
			'transitions' => Status::transitions($this->status),
		];
	}
}