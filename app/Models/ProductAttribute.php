<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Defines a value for a particular trait of a product.
 * @package App\Models
 */
class ProductAttribute extends Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'product-attributes';
	protected $attributes = [

	];
	protected $fillable = [
		'productId',
		'attributeId',
		'label',
		'group',
		'variantAttribute',
		'showInCatalogListing',
		'visibleToCustomers',
		'value',
	];
	protected $casts = [
		'multiValue' => 'bool',
		'variantAttribute' => 'bool',
		'showInCatalogListing' => 'bool',
		'visibleToCustomers' => 'bool',
		'value' => 'array',
	];

	public function product () : BelongsTo
	{
		return $this->belongsTo(Product::class, 'productId');
	}

	public function attribute () : BelongsTo
	{
		return $this->belongsTo(Attribute::class, 'attributeId');
	}
}