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
        if ($this->seller()->exists()) {
            $payload['seller'] = new BusinessDetailResource($this->seller->businessDetails);
        }
        if ($this->seller()->exists()) {
            $payload['order'] = new OrderResource($this);
        }
        $data = [];
        $data[$this->id] = $payload;
        return $data;
    }
}