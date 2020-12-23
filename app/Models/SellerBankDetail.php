<?php

namespace App\Models;

use App\Library\Enums\Common\Directories;
use App\Library\Utils\Uploads;
use App\Queries\Seller\BankDetailQuery;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerBankDetail extends \Illuminate\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'seller_bank_details';
	protected $casts = [
		'addressProofDocument' => 'uri',
		'cancelledCheque' => 'uri',
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
		$this->attributes['addressProofDocument'] = Uploads::access()->putFile(Directories::SellerDocuments, $value);
		return $this->attributes['addressProofDocument'];
	}

	public function setCancelledChequeAttribute ($value)
	{
		$this->attributes['cancelledCheque'] = Uploads::access()->putFile(Directories::SellerDocuments, $value);
		return $this->attributes['cancelledCheque'];
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