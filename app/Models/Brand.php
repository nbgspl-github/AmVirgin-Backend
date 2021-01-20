<?php

namespace App\Models;

use App\Queries\BrandQuery;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Brand
 * @package App\Models
 * @property \App\Library\Enums\Brands\Status $status
 */
class Brand extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;
	use \BenSampo\Enum\Traits\CastsEnums;
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $table = 'brands';

	protected $casts = [
		'active' => 'bool',
		'isBrandOwner' => 'bool',
		'documentExtras' => 'array',
		'status' => \App\Library\Enums\Brands\Status::class
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

	public function setLogoAttribute ($value)
	{
		$this->attributes['logo'] = $this->storeWhenUploadedCorrectly('brands/logos', $value);
	}

	public function getLogoAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['logo']);
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