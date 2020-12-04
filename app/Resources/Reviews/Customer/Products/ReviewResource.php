<?php

namespace App\Resources\Reviews\Customer\Products;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'customer' => new CustomerResource($this->customer),
			'rate' => $this->rate,
			'review' => $this->commentMsg,
			'date' => $this->date
		];
	}
}