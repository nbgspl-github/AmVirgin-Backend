<?php

namespace App\Models;

use App\Queries\BrandQuery;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\GenerateSlugs;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brand extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods, GenerateSlugs;

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
		'requestId'
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

	protected static function boot ()
	{
		parent::boot();
		Brand::saving(function ($brand) {
			$brand->requestId = random_str(25);
		});
	}

	public function category () : BelongsTo
	{
		return $this->belongsTo(Category::class, 'categoryId');
	}

	public static function startQuery () : BrandQuery
	{
		return BrandQuery::begin();
	}
}