<?php

namespace App\Resources\Orders\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        $sellingPrice = $this->product()->exists() ? $this->product->sellingPrice : 0;
        return [
            'quantity' => $this->quantity(),
            'product' => new OrderProductResource($this->product),
            'total' => $this->quantity() * $sellingPrice
        ];
    }
}