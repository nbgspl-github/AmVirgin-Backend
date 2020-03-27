<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use App\Traits\Sluggable;
use BinaryCats\Sku\HasSku;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\SlugOptions;

/**
 * Product is an entity that can be sold, purchased, created, updated and deleted.
 * @package App\Models
 */
class Product extends Model{
	use FluentConstructor, RetrieveResource, RetrieveCollection, HasAttributeMethods, Sluggable, SoftDeletes, HasSku;
	protected $table = 'products';
	protected $fillable = [
		'name',
		'slug',
		'categoryId',
		'sellerId',
		'brandId',
		'listingStatus',
		'originalPrice',
		'sellingPrice',
		'fulfillmentBy',
		'hsn',
		'currency',
		'taxRate',
		'promoted',
		'promotionStart',
		'promotionEnd',
		'rating',
		'stock',
		'draft',
		'shortDescription',
		'longDescription',
		'sku',
		'styleCode',
		'trailer',
		'procurementSla',
		'localShippingCost',
		'zonalShippingCost',
		'internationalShippingCost',
		'packageWeight',
		'packageLength',
		'packageBreadth',
		'packageHeight',
		'domesticWarranty',
		'internationalWarranty',
		'warrantySummary',
		'warrantyServiceType',
		'coveredInWarranty',
		'notCoveredInWarranty',
	];
	protected $hidden = [
		'deleted',
		'created_at',
		'updated_at',
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
	public const WarrantyType = [
		'OnSite' => 'on-site',
		'WalkIn' => 'walk-in',
	];

	public function attributes(): HasMany{
		return $this->hasMany('App\Models\ProductAttribute', 'productId');
	}

	public function images(): HasMany{
		return $this->hasMany('\App\Models\ProductImage', 'productId');
	}

	public function getSlugOptions(): SlugOptions{
		return SlugOptions::create()->saveSlugsTo('slug')->generateSlugsFrom('name');
	}
}