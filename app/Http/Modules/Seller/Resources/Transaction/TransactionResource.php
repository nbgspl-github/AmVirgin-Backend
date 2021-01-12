<?php

namespace App\Http\Modules\Seller\Resources\Transaction;

class TransactionResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'amount' => [
				'requested' => $this->amount_requested,
				'received' => $this->amount_received,
			],
			'paidAt' => $this->paid_at->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
			'bankAccount' => $this->bank_account,
			'referenceId' => $this->reference_id,
			'payments' => \App\Http\Modules\Seller\Resources\Payment\PaymentResource::collection(
				$this->payments
			)
		];
	}
}