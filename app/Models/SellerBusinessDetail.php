<?php

namespace App\Models;

use App\Library\Enums\Common\Directories;
use App\Library\Utils\Uploads;
use App\Queries\Seller\BusinessDetailQuery;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;

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
		$this->attributes['signature'] = Uploads::access()->putFile(Directories::SellerDocuments, $value);
		return $this->attributes['signature'];
	}

	public function setPanProofDocumentAttribute ($value)
	{
		$this->attributes['panProofDocument'] = Uploads::access()->putFile(Directories::SellerDocuments, $value);
		return $this->attributes['panProofDocument'];
	}

	public function setAddressProofDocumentAttribute ($value)
	{
		$this->attributes['addressProofDocument'] = Uploads::access()->putFile(Directories::SellerDocuments, $value);
		return $this->attributes['addressProofDocument'];
	}

	public function setGstCertificateAttribute ($value)
	{
		if ($value instanceof UploadedFile)
			$this->attributes['gstCertificate'] = Uploads::access()->putFile(Directories::SellerDocuments, $value);
		else
			$this->attributes['gstCertificate'] = $value;
		return $this->attributes['gstCertificate'];
	}

	public function getGstCertificateAttribute ($value) : ?string
	{
		return Uploads::existsUrl($this->attributes['gstCertificate']);
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