<?php

namespace App\Resources\Orders\Seller;

use App\Classes\Time;

class OrderCustomerResource extends \Illuminate\Http\Resources\Json\JsonResource{
	public function toArray($request){
		$status = $this->order;
		$status = $status != null ? $status->status() : 'N/A';
		return [
			'key' => $this->id(),
			'name' => $this->name(),
			'orderNumber' => $this->orderNumber(),
			'orderDate' => Time::mysqlStamp(strtotime($this->created_at)),
			'status' => $status,
			'quantity' => $this->items()->sum('quantity'),
		];
	}
}