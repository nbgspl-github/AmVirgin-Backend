<?php

namespace App\Resources\Orders\Customer;

use App\Classes\Time;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderTrackingResource extends JsonResource{
	public function toArray($request){
		return [
			'status' => $this->status(),
			'details' => [
				'arriving' => Time::randomDate(Time::mysqlStamp(), 7),
			],
		];
	}
}