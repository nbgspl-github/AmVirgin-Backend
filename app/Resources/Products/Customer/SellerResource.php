<?php

namespace App\Resources\Products\Customer;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource{
	public function toArray($request){
		return [
			'name' => $this->businessName(),
			'description' => $this->description(),
			'rating' => $this->rating(),
			'avatar' => SecuredDisk::existsUrl($this->avatar()),
		];
	}
}