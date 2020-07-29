<?php


namespace App\Resources\Manifest\Seller;


use App\Classes\Arrays;
use App\Resources\Auth\Seller\BusinessDetailResource;
use App\Resources\Orders\Seller\OrderResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function toArray($request)
    {
        $payload = Arrays::Empty;
        $payload['orderId'] = $this->id;
        $payload['time'] = date("F j, Y, g:i a");
        if ($this->seller()->exists()) {
            $payload['seller'] = new BusinessDetailResource($this->seller->businessDetails);
            $payload['businessName'] = $this->seller->businessName;
        }
        if ($this->seller()->exists()) {
            $payload['order'] = new OrderResource($this);
        }
        return $payload;
    }
}