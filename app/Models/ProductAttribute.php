<?php

namespace App\Models;

use App\Classes\Arrays;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Defines a value for a particular trait of a product.
 * @package App\Models
 */
class ProductAttribute extends Model{
	use RetrieveResource, DynamicAttributeNamedMethods;
	protected $table = 'product-attributes';
	protected $attributes = [
		'value' => [],
	];
	protected $fillable = [
		'productId',
		'attributeId',
		'label',
		'group',
		'variantAttribute',
		'showInCatalogListing',
		'value',
	];
	protected $casts = [
		'multiValue' => 'bool',
		'variantAttribute' => 'bool',
		'value' => 'array',
	];

	public function product(): BelongsTo{
		return $this->belongsTo(Product::class, 'productId');
	}

	public function attribute(): BelongsTo{
		return $this->belongsTo(Attribute::class, 'attributeId');
	}
}