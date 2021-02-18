<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Product\Review;

class ReviewResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'title' => $this->text($this->stars),
			'review' => $this->review,
			'stars' => $this->stars,
			'certified' => $this->certified,
			'customer' => new CustomerResource($this->customer),
			'date' => $this->created_at->format('Y-m-d H:i:s'),
			'images' => ImageResource::collection($this->images)
		];
	}

	protected function text (int $stars) : string
	{
		if ($stars <= 1)
			return "Worst";
		elseif ($stars <= 2)
			return "Bad";
		elseif ($stars <= 3)
			return "Average";
		elseif ($stars <= 4)
			return "Good";
		else
			return "Excellent";
	}
}