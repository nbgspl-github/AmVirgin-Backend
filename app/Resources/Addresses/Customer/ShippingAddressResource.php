<?php

namespace App\Resources\Addresses\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingAddressResource extends JsonResource{
	public static function withoutWrapping(){
		return true;
	}

	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
			'mobile' => $this->mobile(),
			'alternateMobile' => $this->alternateMobile(),
			'pinCode' => $this->pinCode(),
			'city' => new CityResource($this->city),
			'state' => new StateResource($this->state),
			'country' => new CountryResource($this->country),
			'address' => $this->address(),
			'locality' => $this->locality(),
			'type' => $this->type(),
			'saturdayWorking' => $this->saturdayWorking(),
			'sundayWorking' => $this->sundayWorking(),
		];
	}
}