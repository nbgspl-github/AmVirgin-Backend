<?php

namespace App\Resources\Orders\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'courierName' => $this->courierName,
			'awb' => $this->airwayBillNumber
		];
	}
}