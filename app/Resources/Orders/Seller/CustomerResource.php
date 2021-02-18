<?php

namespace App\Resources\Orders\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
			'mobile' => $this->mobile,
			'email' => $this->email
		];
	}
}