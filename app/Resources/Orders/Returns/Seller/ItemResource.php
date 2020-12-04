<?php

namespace App\Resources\Orders\Returns\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'product' => new ProductResource($this->product),
			'quantity' => $this->quantity,
			'price' => [
				'original' => $this->originalPrice,
				'selling' => $this->sellingPrice,
				'calculated' => $this->price,
			],
			'subTotal' => $this->subTotal,
			'total' => $this->total,
			'returnType' => $this->returnType,
			'returnPeriod' => $this->returnPeriod,
			'returnValidUntil' => $this->returnValidUntil
		];
	}
}