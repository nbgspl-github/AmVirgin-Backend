<?php

namespace App\Resources\Orders\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAddressStateResource extends JsonResource{
	public function toArray($request){
		return $this->name();
	}
}