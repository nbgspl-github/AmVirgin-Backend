<?php

namespace App\Models;

use App\Classes\Str;
use App\Queries\BrandQuery;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use App\Traits\GenerateSlugs;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;

class Brand extends Model{
	use DynamicAttributeNamedMethods, RetrieveResource, GenerateSlugs;
	protected $table = 'brands';
	protected $fillable = [
		'name',
		'slug',
		'logo',
		'website',
		'productSaleMarketPlace',
		'sampleMRPTagImage',
		'isBrandOwner',
		'documentProof',
		'categoryId',
		'createdBy',
		'status',
		'active',
		'documentExtras',
	];
	protected $casts = [
		'active' => 'bool',
		'isBrandOwner' => 'bool',
		'documentExtras' => 'array',
	];
	protected $hidden = [
		'created_at', 'updated_at', 'id',
	];
	public const Status = [
		'Approved' => 'approved',
		'Rejected' => 'rejected',
		'Pending' => 'pending',
	];
	public const DocumentType = [
		'TrademarkCertificate' => 'trademark-certificate',
		'BrandAuthorizationLetter' => 'brand-authorization-letter',
		'Invoice' => 'invoice',
		'Other' => 'other',
	];

	public static function startQuery(): BrandQuery{
		return BrandQuery::begin();
	}
}