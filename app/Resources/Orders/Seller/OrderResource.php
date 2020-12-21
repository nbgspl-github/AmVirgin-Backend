<?php

namespace App\Resources\Orders\Seller;

use App\Library\Enums\Orders\Payments\Methods;
use App\Library\Enums\Orders\Status;
use App\Models\Order;
use App\Resources\Orders\Customer\AddressResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderResource
 * @package App\Resources\Orders\Seller
 * @property Status $status
 * @property ?Order $order
 */
class OrderResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'status' => $this->status,
			'number' => $this->order->orderNumber,
			'placed' => $this->created_at->format('Y-m-d H:i:s'),
			'prepaid' => $this->isPrepaid(),
			'customer' => new CustomerResource($this->customer),
			'address' => new AddressResource($this->order->address),
			'items' => OrderItemResource::collection($this->items),
			'transitions' => Status::transitions($this->status),
		];
	}

	protected function isPrepaid () : bool
	{
		return $this->order->paymentMode->isNot(Methods::CashOnDelivery);
	}
}