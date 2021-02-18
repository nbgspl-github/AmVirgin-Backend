<?php

namespace App\Models;

use App\Queries\Seller\BusinessDetailQuery;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerBusinessDetail extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'seller_business_details';
	protected $casts = [
		'signatureVerified' => 'bool',
		'gstINVerified' => 'bool',
		'panVerified' => 'bool',
		'nameVerified' => 'bool',
	];

	public function setSignatureAttribute ($value)
	{
		($value instanceof \Illuminate\Http\UploadedFile)
			? $this->attributes['signature'] = $this->storeMedia('seller-documents', $value)
			: $this->attributes['signature'] = $value;
	}

	public function getSignatureAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['signature']);
	}

	public function setPanProofDocumentAttribute ($value)
	{
		($value instanceof \Illuminate\Http\UploadedFile)
			? $this->attributes['panProofDocument'] = $this->storeMedia('seller-documents', $value)
			: $this->attributes['panProofDocument'] = $value;
	}

	public function getPanProofDocumentAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['panProofDocument']);
	}

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

	public function setGstCertificateAttribute ($value)
	{
		($value instanceof \Illuminate\Http\UploadedFile)
			? $this->attributes['gstCertificate'] = $this->storeMedia('seller-documents', $value)
			: $this->attributes['gstCertificate'] = $value;
	}

	public function getGstCertificateAttribute ($value) : ?string
	{
		return $this->retrieveMedia($this->attributes['gstCertificate']);
	}

	public function state () : BelongsTo
	{
		return $this->belongsTo(State::class, 'rbaStateId');
	}

	public function city () : BelongsTo
	{
		return $this->belongsTo(City::class, 'rbaCityId');
	}

	public function country ()
	{
		return Country::where('initials', 'IN')->first();
	}

	public static function startQuery () : BusinessDetailQuery
	{
		return BusinessDetailQuery::begin();
	}
}