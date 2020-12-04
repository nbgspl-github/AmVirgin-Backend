<?php

namespace App\Resources\Orders\Returns\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'key' => $this->id,
			'item' => new ItemResource($this->item),
			'customer' => new CustomerResource($this->customer),
			'status' => $this->status,
			'segment' => new OrderSegmentResource($this->segment)
		];
	}
}