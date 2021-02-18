<?php

namespace App\Resources\Auth\Seller;

class BusinessDetailResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return [
            'name' => $this->name(),
            'nameVerified' => $this->nameVerified(),
            'tan' => $this->tan(),
            'gstIN' => $this->gstIN(),
            'gstCertificate' => $this->gstCertificate(),
            'gstINVerified' => $this->gstINVerified(),
            'signature' => $this->signature(),
            'signatureVerified' => $this->signatureVerified(),
            'rbaFirstLine' => $this->rbaFirstLine(),
            'rbaSecondLine' => $this->rbaSecondLine(),
            'rbaPinCode' => $this->rbaPinCode(),
            'rbaCity' => new CityResource($this->city),
            'rbaState' => new StateResource($this->state),
            'rbaCountry' => new CountryResource($this->country()),
            'pan' => [
                'pan_no' => $this->pan(),
                'document' => $this->panProofDocument(),
                'verified' => $this->panVerified(),
            ],
            'addressProof' => [
                'type' => $this->addressProofType(),
                'document' => $this->addressProofDocument(),
                'verified' => $this->addressProofVerified(),
            ],
        ];
    }
}