<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\SlugOptions;

/**
 * Product is an entity that can be sold, purchased, created, updated and deleted.
 * @package App\Models
 */
class Product extends Model {
	use FluentConstructor, RetrieveResource, RetrieveCollection, HasAttributeMethods, Sluggable, SoftDeletes;
	protected $table = 'products';
	protected $fillable = [
		'name',
		'slug',
		'categoryId',
		'sellerId',
		'productType',
		'productMode',
		'listingType',
		'originalPrice',
		'offerValue',
		'offerType',
		'currency',
		'taxRate',
		'countryId',
		'stateId',
		'cityId',
		'zipCode',
		'address',
		'promoted',
		'promotionStart',
		'promotionEnd',
		'visibility',
		'rating',
		'stock',
		'shippingCostType',
		'shippingCost',
		'soldOut',
		'deleted',
		'draft',
		'shortDescription',
		'longDescription',
		'sku',
		'trailer',
		'sellingPrice',
		'hsn',
		'taxCode',
		'fulfilmentBy',
		'procurementSla',
		'localShippingCost',
		'zonalShippingCost',
		'internationalShippingCost',
		'packageWeight',
		'packageLength',
		'packageHeight',
		'idealFor',
		'videoUrl',
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
		'visibility' => 'bool',
		'draft' => 'bool',
		'soldOut' => 'bool',
	];
	const ListingStatus = [
		'Active' => 'active',
		'Inactive' => 'inactive',
	];
	const FulfillmentBy = [
		'Seller' => 'seller',
		'SellerSmart' => 'seller-smart',
	];
	const ShippingCost = [
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
	const ProcurementSLA = [
		'Minimum' => 0,
		'Maximum' => 7,
	];
	const Weight = [
		'Minimum' => 0,
		'Maximum' => 1000,
	];
	const Dimensions = [
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
	const Warranty = [
		'Domestic' => [
			'Minimum' => 0,
			'Maximum' => 300,
		],
		'International' => [
			'Minimum' => 0,
			'Maximum' => 300,
		],
	];
	const WarrantyType = [
		'OnSite' => 'on-site',
		'WalkIn' => 'walk-in',
	];

	/**
	 * @return HasMany
	 */
	public function attributes() {
		return $this->hasMany('App\Models\ProductAttribute', 'productId');
	}

	/**
	 * @return HasMany
	 */
	public function images() {
		return $this->hasMany('\App\Models\ProductImage', 'productId');
	}

	/**
	 * @return SlugOptions
	 */
	public function getSlugOptions(): SlugOptions {
		return SlugOptions::create()->saveSlugsTo('slug')->generateSlugsFrom('name');
	}
}