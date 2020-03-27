<?php

namespace App\Models;

use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

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

	public function product(){
		return $this->belongsTo(Product::class, 'productId');
	}

	public function attribute(){
		return $this->belongsTo(Attribute::class, 'attributeId');
	}
}