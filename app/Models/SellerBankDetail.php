<?php

namespace App\Models;

use App\Classes\Arrays;
use App\Classes\Eloquent\ModelExtended;
use App\Classes\Rule;
use App\Interfaces\Directories;
use App\Interfaces\Tables;
use App\Queries\SellerBankDetailQuery;
use App\Storage\SecuredDisk;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerBankDetail extends ModelExtended{
	use DynamicAttributeNamedMethods;

	protected $fillable = [
		'sellerId',
		'accountHolderName',
		'accountNumber',
		'bankName',
		'city',
		'state',
		'country',
		'branch',
		'ifsc',
		'businessType',
		'pan',
		'addressProofType',
		'addressProofDocument',
		'cancelledCheque',
	];
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
	];

	public function setAddressProofDocumentAttribute($value){
		$this->attributes['addressProofDocument'] = SecuredDisk::access()->putFile(Directories::SellerDocuments, $value);
		return $this->attributes['addressProofDocument'];
	}

	public function setCancelledChequeAttribute($value){
		$this->attributes['cancelledCheque'] = SecuredDisk::access()->putFile(Directories::SellerDocuments, $value);
		return $this->attributes['cancelledCheque'];
	}

	public function state(): BelongsTo{
		return $this->belongsTo('App\Models\State', 'stateId');
	}

	public function city(): BelongsTo{
		return $this->belongsTo('App\Models\City', 'cityId');
	}

	public function country(){
		return Country::where('initials', 'IN')->first();
	}

	public static function startQuery(): SellerBankDetailQuery{
		return SellerBankDetailQuery::begin();
	}
}
