<?php

namespace App\Resources\Orders\Seller;

use App\Classes\Time;
use App\Enums\Seller\OrderStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request)
	{
		$status = $this->order;
		$status = $status != null ? $status->status() : 'N/A';
		return [
			'key' => $this->id,
			'orderId' => $this->orderId,
			'orderNumber' => $this->orderNumber,
			'orderDate' => Time::mysqlStamp(strtotime($this->created_at)),
			'status' => $status,
			'quantity' => $this->items()->sum('quantity'),
			'customer' => new OrderCustomerResource($this->customer),
			'cancellationReason' => $this->when($status == OrderStatus::Cancelled, $this->cancellationReason),
			'cancelledBy' => $this->when($status == OrderStatus::Cancelled, $this->cancelledBy),
			'cancelledOn' => $this->when($status == OrderStatus::Cancelled, Time::mysqlStamp(strtotime($this->cancelledOn))),
			'dispatchedOn' => $this->when($status == OrderStatus::Dispatched || $status == OrderStatus::ReadyForDispatch, Time::mysqlStamp(strtotime($this->dispatchedOn))),
			'deliveredOn' => $this->when($status == OrderStatus::Delivered, Time::mysqlStamp(strtotime($this->deliveredOn))),
		];
	}
}