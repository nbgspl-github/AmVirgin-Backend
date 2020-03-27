<?php

namespace App\Models;

use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Defines a value for a particular trait of a product.
 * @package App\Models
 */
class ProductAttribute extends Model{
	use RetrieveResource, HasAttributeMethods;
	protected $table = 'product-attributes';
	protected $fillable = [
		'productId',
		'attributeId',
		'valueId',
		'value',
	];

	public function product(): BelongsTo{
		return $this->belongsTo(Product::class, 'productId');
	}

	public function attribute(): BelongsTo{
		return $this->belongsTo(Attribute::class, 'attributeId');
	}
}