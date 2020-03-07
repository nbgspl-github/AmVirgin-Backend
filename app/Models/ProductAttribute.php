<?php

namespace App\Models;

use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model {
	use RetrieveResource;
	protected $table = 'product-attributes';
	protected $fillable = [
		'productId',
		'attributeId',
		'valueId',
	];
}
