<?php

namespace App\Resources\Payments\Transactions\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->orderId,
			'date' => $this->created_at->format('Y-m-d'),
			'transactionId' => $this->rzpOrderId,
			'amount' => [
				'requested' => $this->amountRequested,
				'received' => $this->amountReceived,
			],
		];
	}
}