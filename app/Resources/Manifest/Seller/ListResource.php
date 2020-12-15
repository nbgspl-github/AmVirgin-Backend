<?php

namespace App\Resources\Manifest\Seller;

use App\Models\Auth\Seller;
use App\Resources\Auth\Seller\BusinessDetailResource;
use App\Resources\Orders\Seller\OrderResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ListResource
 * @package App\Resources\Manifest\Seller
 * @property ?Seller $seller
 */
class ListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		$payload = [];
		$payload['orderId'] = $this->id;
		$payload['time'] = date("F j, Y, g:i a");
		if ($this->seller != null) {
			$payload['seller'] = new BusinessDetailResource($this->seller->businessDetails);
			$payload['businessName'] = $this->seller->businessName;
		}
		if ($this->seller != null) {
			$payload['order'] = new OrderResource($this);
		}
		return $payload;
	}
}