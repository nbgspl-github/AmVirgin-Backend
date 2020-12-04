<?php

namespace App\Resources\Reviews\Customer\Products;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'name' => $this->name,
			'avatar' => $this->avatar,
		];
	}
}