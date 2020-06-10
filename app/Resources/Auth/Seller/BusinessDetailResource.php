<?php

namespace App\Resources\Auth\Seller;

use App\Models\City;
use App\Models\Country;
use App\Models\State;

class BusinessDetailResource extends \Illuminate\Http\Resources\Json\JsonResource{
	public function __construct($resource){
		parent::__construct($resource);
	}

	public function toArray($request){
		return [
			'name' => $this->name(),
			'nameVerified' => $this->nameVerified(),
			'tan' => $this->tan(),
			'gstIN' => $this->gstIN(),
			'gstINVerified' => $this->gstINVerified(),
			'signature' => $this->signature(),
			'signatureVerified' => $this->signatureVerified(),
			'rbaFirstLine' => $this->rbaFirstLine(),
			'rbaSecondLine' => $this->rbaSecondLine(),
			'rbaPinCode' => $this->rbaPinCode(),
			'rbaCity' => new CityResource($this->city),
			'rbaState' => new StateResource($this->state),
			'rbaCountry' => new CountryResource($this->country()),
		];
	}
}