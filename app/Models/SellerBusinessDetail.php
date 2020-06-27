<?php

namespace App\Models;

use App\Classes\Arrays;
use App\Classes\Eloquent\ModelExtended;
use App\Classes\Rule;
use App\Interfaces\Directories;
use App\Interfaces\Tables;
use App\Queries\Seller\BankDetailQuery;
use App\Queries\Seller\BusinessDetailQuery;
use App\Storage\SecuredDisk;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerBusinessDetail extends ModelExtended{
	use DynamicAttributeNamedMethods;

	protected $fillable = [
		'sellerId',
		'name',
		'nameVerified',
		'tan',
		'gstIN',
		'gstINVerified',
		'signature',
		'signatureVerified',
		'rbaFirstLine',
		'rbaSecondLine',
		'rbaPinCode',
		'rbaCityId',
		'rbaStateId',
		'rbaCountryId',
		'pan',
		'panVerified',
		'panProofDocument',
	];
	protected $casts = [
		'signature' => 'uri',
		'signatureVerified' => 'bool',
		'gstINVerified' => 'bool',
		'nameVerified' => 'bool',
	];

	public function setSignatureAttribute($value){
		$this->attributes['signature'] = SecuredDisk::access()->putFile(Directories::SellerDocuments, $value);
		return $this->attributes['signature'];
	}
	public function setPanProofDocumentAttribute($value){
		$this->attributes['panProofDocument'] = SecuredDisk::access()->putFile(Directories::SellerDocuments, $value);
		return $this->attributes['panProofDocument'];
	}

	public function state(): BelongsTo{
		return $this->belongsTo(State::class, 'rbaStateId');
	}

	public function city(): BelongsTo{
		return $this->belongsTo(City::class, 'rbaCityId');
	}

	public function country(){
		return Country::where('initials', 'IN')->first();
	}

	public static function startQuery(): BusinessDetailQuery{
		return BusinessDetailQuery::begin();
	}
}