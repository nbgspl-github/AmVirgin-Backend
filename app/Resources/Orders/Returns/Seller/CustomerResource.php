<?php

namespace App\Resources\Orders\Returns\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
		];
	}
}