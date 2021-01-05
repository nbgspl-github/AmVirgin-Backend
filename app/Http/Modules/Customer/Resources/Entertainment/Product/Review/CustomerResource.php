<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Product\Review;

class CustomerResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
		];
	}
}