<?php

namespace App\Resources\Orders\Seller;

use App\Classes\Time;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource{
	public function __construct($resource){
		parent::__construct($resource);
	}

	public function toArray($request){
		$status = $this->order;
		$status = $status != null ? $status->status() : 'N/A';
		return [
			'orderId' => $this->orderId(),
			'orderNumber' => $this->orderNumber(),
			'orderDate' => Time::mysqlStamp(strtotime($this->created_at)),
			'status' => $status,
			'quantity' => $this->items()->sum('quantity'),
			'customerId' => $this->customerId(),
			'customer' => $this->customer,
		];
	}
}