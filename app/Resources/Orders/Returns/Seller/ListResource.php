<?php

namespace App\Resources\Orders\Returns\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request): array
	{
		return [
			'key' => $this->id,
			'item' => new ItemResource($this->item),
			'order' => new OrderSegmentResource($this->segment),
			'customer' => new CustomerResource($this->customer),
			'status' => $this->status,
			'raised' => $this->raised_at,
			'reason' => $this->reason
		];
	}
}