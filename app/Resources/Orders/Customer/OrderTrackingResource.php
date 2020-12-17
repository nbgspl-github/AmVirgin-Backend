<?php

namespace App\Resources\Orders\Customer;

use App\Library\Utils\Extensions\Time;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderTrackingResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'status' => $this->status,
			'details' => [
				'arriving' => Time::randomDate(Time::mysqlStamp(), 7),
			],
		];
	}
}