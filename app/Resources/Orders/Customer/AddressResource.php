<?php

namespace App\Resources\Orders\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'name' => $this->name,
			'mobile' => $this->mobile,
			'pinCode' => $this->pinCode,
			'address' => $this->address,
			'locality' => $this->locality,
			'type' => $this->type,
			'saturdayWorking' => $this->saturdayWorking,
			'sundayWorking' => $this->sundayWorking,
		];
	}
}