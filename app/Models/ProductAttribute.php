<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Defines a value for a particular trait of a product.
 * @package App\Models
 */
class ProductAttribute extends Model{
	use RetrieveResource, DynamicAttributeNamedMethods;
	protected $table = 'product-attributes';
	protected $fillable = [
		'productId',
		'attributeId',
		'value',
	];

	public function product(): BelongsTo{
		return $this->belongsTo(Product::class, 'productId');
	}

	public function attribute(): BelongsTo{
		return $this->belongsTo(Attribute::class, 'attributeId');
	}
}