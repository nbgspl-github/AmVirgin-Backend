<?php

namespace App\Resources\Orders\Seller;

use App\Library\Utils\Extensions\Time;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentListResource extends JsonResource{
	public function __construct($resource){
		parent::__construct($resource);
	}

	public function toArray($request){
		$order=$this->order;
		$status = $this->order;
		$status = $status != null ? $status->status() : 'N/A';
		return [
			'key' => $this->id(),
			'orderId' => $this->orderId(),
			'orderNumber' => $this->orderNumber(),
			'orderDate' => Time::mysqlStamp(strtotime($this->created_at)),
			'status' => $status,
			'transactionId' => $order->transactionId ?? '',
			'tax' => $order->tax ?? '',
			'subTotal' => $order->subTotal ?? '',
			'total' => $order->total ?? '',
			'paymentMode' => $order->paymentMode ?? '',
			'quantity' => $this->items()->sum('quantity'),
			'customer' => new CustomerResource($this->customer),
		];
	}
}