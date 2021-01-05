<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Product\Review;

class ReviewResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'review' => $this->review,
			'stars' => $this->stars,
			'certified' => $this->certified,
			'customer' => new CustomerResource($this->customer),
			'date' => $this->created_at->format('Y-m-d H:i:s'),
			'images' => ImageResource::collection($this->images)
		];
	}
}