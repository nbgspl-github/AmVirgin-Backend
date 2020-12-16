<?php

namespace App\Resources\Orders\Seller;

use App\Library\Enums\Orders\Status;
use App\Library\Utils\Extensions\Time;
use App\Models\Auth\Seller;
use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ListResource
 * @package App\Resources\Orders\Seller
 * @property ?Order $order
 * @property ?Seller $seller
 * @property Status $status
 * @property ?int $orderId
 * @property int $quantity
 */
class ListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'orderDate' => Time::mysqlStamp(strtotime($this->created_at)),
			'status' => $this->status,
			'quantity' => $this->quantity,
			'customer' => new OrderCustomerResource($this->customer),
			'cancellationReason' => $this->when($this->status->is(Status::Cancelled), $this->cancellationReason),
			'cancelledBy' => $this->when($this->status->is(Status::Cancelled), $this->cancelledBy),
			'cancelledOn' => $this->when($this->status->is(Status::Cancelled), Time::mysqlStamp(strtotime($this->cancelledOn))),
			'dispatchedOn' => $this->when($this->status->is(Status::Dispatched) || $this->status->is(Status::ReadyForDispatch), Time::mysqlStamp(strtotime($this->dispatchedOn))),
			'deliveredOn' => $this->when($this->status->is(Status::Delivered), Time::mysqlStamp(strtotime($this->deliveredOn))),
		];
	}
}