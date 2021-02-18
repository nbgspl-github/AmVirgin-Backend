<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Refers to one or more images associated with a product.
 * @package App\Models
 */
class ProductImage extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'product-images';

	public function getPathAttribute ($value) : ?string
	{
		return $this->retrieveMedia($value);
	}

	public function product () : BelongsTo
	{
		return $this->belongsTo(Product::class, 'productId');
	}
}