<?php

namespace App\Http\Modules\Seller\Resources\Payment;

class PaymentResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->sub_order_id,
			'date' => $this->created_at->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
			'description' => $this->description,
			'quantity' => $this->quantity,
			'sales' => $this->sales,
			'sellingFee' => $this->selling_fee,
			'courierCharges' => $this->courier_charges,
			'total' => $this->total
		];
	}
}