<?php

namespace App\Resources\Auth\Seller;

use App\Models\City;
use App\Models\Country;
use App\Models\State;

class BankDetailResource extends \Illuminate\Http\Resources\Json\JsonResource{
	public function __construct($resource){
		parent::__construct($resource);
	}

	public function toArray($request){
		return [
			'accountHolderName' => $this->accountHolderName(),
			'accountNumber' => $this->accountNumber(),
			'accountNumberVerified' => $this->accountNumberVerified(),
			'bankName' => $this->bankName(),
			'city' => new CityResource($this->city),
			'state' => new StateResource($this->state),
			'country' => new CountryResource($this->country),
			'branch' => $this->branch(),
			'ifsc' => $this->ifsc(),
			'businessType' => $this->businessType(),
			'pan' => $this->pan(),
			'panVerified' => $this->panVerified(),
			'addressProof' => [
				'type' => $this->addressProofType(),
				'document' => $this->addressProofDocument(),
				'verified' => $this->addressProofVerified(),
			],
			'cancelledCheque' => $this->cancelledCheque(),
			'cancelledChequeVerified' => $this->cancelledChequeVerified(),
		];
	}
}