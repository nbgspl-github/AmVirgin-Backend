<?php

namespace App\Resources\Products\Customer;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource{
	public function toArray($request){
		return [
			'name' => $this->businessName(),
			'description' => $this->description(),
			'rating' => $this->rating(),
			'avatar' => Uploads::existsUrl($this->avatar()),
		];
	}
}