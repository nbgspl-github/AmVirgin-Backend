<?php

namespace App\Resources\Support\Seller;

class TicketResource extends \Illuminate\Http\Resources\Json\JsonResource {
	public function toArray ($request) {
		return [
			'key' => $this->id(),
			'subject' => $this->subject(),
			'description' => $this->description(),
			'orderId' => $this->orderId(),
			'callbackNumber' => $this->callbackNumber(),
			'attachments' => $this->attachments(),
			'status' => $this->status(),
		];
	}
}