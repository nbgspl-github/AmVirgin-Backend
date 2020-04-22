<?php

namespace App\Resources\Orders\Seller;

use App\Classes\Time;

class OrderCustomerResource extends \Illuminate\Http\Resources\Json\JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
		];
	}
}