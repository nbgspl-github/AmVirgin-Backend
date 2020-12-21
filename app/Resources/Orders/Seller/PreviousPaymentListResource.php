<?php

namespace App\Resources\Orders\Seller;

use App\Library\Utils\Extensions\Str;
use App\Library\Utils\Extensions\Time;
use Illuminate\Http\Resources\Json\JsonResource;

class PreviousPaymentListResource extends JsonResource
{
	public function __construct ($resource)
	{
		parent::__construct($resource);
	}

	public function toArray ($request)
    {
        $sellerBank = $this->sellerBank;
        $order = $this->order;
        $status = $this->order;
        $status = $status != null ? $status->status() : 'N/A';
        return [
	        'key' => $this->id(),
	        'orderId' => $this->orderId(),
	        'orderNumber' => $this->orderNumber(),
	        'paymentDate' => Time::mysqlStamp(strtotime($this->created_at)),
	        'transactionId' => $order->transactionId ?? Str::Empty,
	        'neftId' => !empty($order->transactionId) ? str_replace("pay_", "", $order->transactionId) : Str::Empty,
	        'total' => $order->total ?? '',
	        'bankDetails' => $sellerBank,
	        'quantity' => $this->items()->sum('quantity'),
	        'customer' => new CustomerResource($this->customer),
        ];
    }
}