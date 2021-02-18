<?php

namespace App\Resources\Payments\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'paidAt' => $this->paid_at,
			'account' => $this->bank_account,
			'transactionId' => $this->transaction_id,
			'neftId' => $this->neft_id,
			'amount' => $this->amount
		];
	}
}