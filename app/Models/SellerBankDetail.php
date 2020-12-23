<?php

namespace App\Models;

use App\Queries\Seller\BankDetailQuery;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerBankDetail extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'seller_bank_details';
	protected $casts = [
		'accountNumberVerified' => 'bool',
		'panVerified' => 'bool',
		'addressProofVerified' => 'bool',
		'cancelledChequeVerified' => 'bool',
	];

	public const AddressProofType = [
		'AadharCard' => 'aadhar-card',
		'VoterID' => 'voter-id',
		'DrivingLicense' => 'driving-license',
		'ElectrycityBill' => 'electrycity-bill',
		'PhoneBill' => 'pbone-bill',
	];

	public function setAddressProofDocumentAttribute ($value)
	{
		($value instanceof \Illuminate\Http\UploadedFile)
			? $this->attributes['addressProofDocument'] = $this->storeMedia('seller-documents', $value)
			: $this->attributes['addressProofDocument'] = $value;
	}

	public function getAddressProofDocumentAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['addressProofDocument']);
	}

	public function setCancelledChequeAttribute ($value)
	{
		($value instanceof \Illuminate\Http\UploadedFile)
			? $this->attributes['cancelledCheque'] = $this->storeMedia('seller-documents', $value)
			: $this->attributes['cancelledCheque'] = $value;
	}

	public function getCancelledChequeAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['cancelledCheque']);
	}

	public function state () : BelongsTo
	{
		return $this->belongsTo(State::class, 'stateId');
	}

	public function city () : BelongsTo
	{
		return $this->belongsTo(City::class, 'cityId');
	}

	public function country ()
	{
		return Country::where('initials', 'IN')->first();
	}

	public static function startQuery () : BankDetailQuery
	{
		return BankDetailQuery::begin();
	}
}