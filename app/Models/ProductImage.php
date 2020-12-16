<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Refers to one or more images associated with a product.
 * @package App\Models
 */
class ProductImage extends Model{
	use RetrieveResource, RetrieveCollection, DynamicAttributeNamedMethods;
	protected $table = 'product-images';
	protected $fillable = [
		'productId',
		'path',
	];
	protected $hidden = [
		'id',
		'productId',
		'created_at',
		'updated_at',
	];

	public function product(): BelongsTo{
		return $this->belongsTo(Product::class, 'productId');
	}
}