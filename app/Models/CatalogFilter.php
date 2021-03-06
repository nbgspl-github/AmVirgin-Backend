<?php

namespace App\Models;

use App\Queries\CatalogFilterQuery;
use App\Resources\Shop\Customer\Catalog\Filters\BrandResource;
use App\Resources\Shop\Customer\Catalog\Filters\CategoryResource;
use App\Resources\Shop\Customer\Catalog\Filters\DiscountResource;
use App\Resources\Shop\Customer\Catalog\Filters\GenderResource;
use App\Resources\Shop\Customer\Catalog\Filters\PriceRangeResource;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogFilter extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'catalog_filters';
	protected $casts = [
		'active' => 'bool',
		'builtIn' => 'bool',
		'allowMultiValue' => 'bool',
	];
	public const BuiltInFilters = [
		'Brand' => 'brand',
		'Category' => 'category',
		'Discount' => 'discount',
		'Gender' => 'gender',
		'Price Range' => 'price',
	];
	public const AllowMultiValueDefault = [
		'brand' => true,
		'category' => true,
		'discount' => false,
		'gender' => false,
		'price' => true,
	];
	public const BuiltInFilterResourceMapping = [
		'brand' => BrandResource::class,
		'category' => CategoryResource::class,
		'discount' => DiscountResource::class,
		'gender' => GenderResource::class,
		'price' => PriceRangeResource::class,
	];

	public function attribute () : BelongsTo
	{
		return $this->belongsTo(Attribute::class, 'attributeId');
	}

	public function category () : BelongsTo
	{
		return $this->belongsTo(Category::class, 'categoryId');
	}

	public static function startQuery () : CatalogFilterQuery
	{
		return CatalogFilterQuery::begin();
	}
}