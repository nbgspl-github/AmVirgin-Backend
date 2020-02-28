<?php

namespace App\Resources\Addresses\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingAddressResource extends JsonResource {
	public static function withoutWrapping() {
		return true;
	}

	public function toArray($request) {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'mobile' => $this->mobile,
			'alternateMobile' => $this->alternateMobile,
			'pinCode' => $this->pinCode,
			'state' => $this->state,
			'address' => $this->address,
			'locality' => $this->locality,
			'city' => $this->city,
			'type' => $this->type,
			'saturdayWorking' => $this->saturdayWorking,
			'sundayWorking' => $this->sundayWorking,
		];
	}
}