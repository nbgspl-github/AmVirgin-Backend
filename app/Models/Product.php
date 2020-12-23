<?php

namespace App\Models;

use App\Models\Auth\Seller;
use App\Queries\ProductQuery;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\GenerateSlugs;
use App\Traits\HasInventoryStocks;
use App\Traits\HasSpecialAttributes;
use BinaryCats\Sku\HasSku;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Product is an entity that can be sold, purchased, created, updated and deleted.
 * @package App\Models
 */
class Product extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods, HasSpecialAttributes, GenerateSlugs, SoftDeletes, HasSku, HasInventoryStocks;

	protected $hidden = [
		'id',
		'deletedAt',
		'createdAt',
		'updatedAt',
		'sellerId',
	];
	protected $casts = [
		'draft' => 'bool',
	];
	public const ListingStatus = [
		'Active' => 'active',
		'Inactive' => 'inactive',
	];
	public const FulfillmentBy = [
		'Seller' => 'seller',
		'SellerSmart' => 'seller-smart',
	];
	public const ShippingCost = [
		'Local' => [
			'Minimum' => 0,
			'Maximum' => 10000,
		],
		'Zonal' => [
			'Minimum' => 0,
			'Maximum' => 10000,
		],
		'International' => [
			'Minimum' => 0,
			'Maximum' => 10000,
		],
	];
	public const ProcurementSLA = [
		'Minimum' => 0,
		'Maximum' => 7,
	];
	public const Weight = [
		'Minimum' => 0,
		'Maximum' => 1000,
	];
	public const Dimensions = [
		'Length' => [
			'Minimum' => 0,
			'Maximum' => 10000,
		],
		'Breadth' => [
			'Minimum' => 0,
			'Maximum' => 10000,
		],
		'Height' => [
			'Minimum' => 0,
			'Maximum' => 10000,
		],
	];
	public const Warranty = [
		'Domestic' => [
			'Minimum' => 0,
			'Maximum' => 300,
		],
		'International' => [
			'Minimum' => 0,
			'Maximum' => 300,
		],
	];
	public const WarrantyServiceType = [
		'Walk In' => 'walk-in',
		'On Site' => 'on-site',
	];
	public const Type = [
		'Simple' => 'simple',
		'Variant' => 'variant',
	];
	public const IdealFor = [
		'Men' => 'men',
		'Women' => 'women',
		'Boys' => 'boys',
		'Girls' => 'girls',
	];

	public function shippingCost () : float
	{
		return 3.3;
	}

	public function attributes () : HasMany
	{
		return $this->hasMany(ProductAttribute::class, 'productId');
	}

	public function options () : HasMany
	{
		return $this->hasMany(ProductAttribute::class, 'productId')->where('variantAttribute', true);
	}

	public function specs () : HasMany
	{
		return $this->hasMany(ProductAttribute::class, 'productId')->where('variantAttribute', false);
	}

	public function brand () : BelongsTo
	{
		return $this->belongsTo(Brand::class, 'brandId');
	}

	public function category () : BelongsTo
	{
		return $this->belongsTo(Category::class, 'categoryId');
	}

	public function images () : HasMany
	{
		return $this->hasMany(ProductImage::class, 'productId');
	}

	public function seller () : BelongsTo
	{
		return $this->belongsTo(Seller::class, 'sellerId');
	}

	public function variants () : HasMany
	{
		return $this->hasMany(self::class, 'group', 'group')->where('id', '!=', $this->id);
	}

	public function ratings () : HasMany
	{
		return $this->hasMany(ProductRating::class);
	}

	public function hotDeal (?bool $yes = null) : bool
	{
		if (!is_null($yes)) {
			$this->setSpecialAttribute('hotDeal', $yes);
		}
		return $this->getSpecialAttribute('hotDeal', false);
	}

	public static function startQuery () : ProductQuery
	{
		return ProductQuery::begin();
	}
}