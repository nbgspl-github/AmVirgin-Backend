<?php

namespace App\Resources\Auth\Seller;

class BankDetailResource extends \Illuminate\Http\Resources\Json\JsonResource{
	public function toArray($request){
		return [
			'accountHolderName' => $this->accountHolderName(),
			'accountNumber' => $this->accountNumber(),
			'accountNumberVerified' => $this->accountNumberVerified(),
			'bankName' => $this->bankName(),
			'city' => new CityResource($this->city),
			'state' => new CityResource($this->state),
			'country' => new CityResource($this->country),
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