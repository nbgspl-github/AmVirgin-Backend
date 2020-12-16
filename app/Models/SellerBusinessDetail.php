<?php

namespace App\Models;

use App\Classes\Eloquent\ModelExtended;
use App\Library\Enums\Common\Directories;
use App\Queries\Seller\BusinessDetailQuery;
use App\Storage\SecuredDisk;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;

class SellerBusinessDetail extends ModelExtended
{
    use DynamicAttributeNamedMethods;

    protected $fillable = [
        'sellerId',
        'name',
        'nameVerified',
        'tan',
        'gstIN',
        'gstCertificate',
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
        'addressProofType',
        'addressProofDocument',
    ];
    protected $casts = [
        'signature' => 'uri',
        'addressProofDocument' => 'uri',
        'signatureVerified' => 'bool',
        'gstINVerified' => 'bool',
        'panVerified' => 'bool',
        'panProofDocument' => 'uri',
        'nameVerified' => 'bool',
    ];

    public function setSignatureAttribute($value)
    {
        $this->attributes['signature'] = SecuredDisk::access()->putFile(Directories::SellerDocuments, $value);
        return $this->attributes['signature'];
    }

    public function setPanProofDocumentAttribute($value)
    {
        $this->attributes['panProofDocument'] = SecuredDisk::access()->putFile(Directories::SellerDocuments, $value);
        return $this->attributes['panProofDocument'];
    }

    public function setAddressProofDocumentAttribute($value)
    {
        $this->attributes['addressProofDocument'] = SecuredDisk::access()->putFile(Directories::SellerDocuments, $value);
        return $this->attributes['addressProofDocument'];
    }

    public function setGstCertificateAttribute($value)
    {
        if ($value instanceof UploadedFile)
            $this->attributes['gstCertificate'] = SecuredDisk::access()->putFile(Directories::SellerDocuments, $value);
        else
            $this->attributes['gstCertificate'] = $value;
        return $this->attributes['gstCertificate'];
    }

    public function getGstCertificateAttribute($value)
    {
        return SecuredDisk::existsUrl($this->attributes['gstCertificate']);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'rbaStateId');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'rbaCityId');
    }

    public function country()
    {
        return Country::where('initials', 'IN')->first();
    }

    public static function startQuery(): BusinessDetailQuery
    {
        return BusinessDetailQuery::begin();
    }
}