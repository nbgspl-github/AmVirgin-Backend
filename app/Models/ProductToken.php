<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;

class ProductToken extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'product-tokens';
	protected $fillable = [
		'token',
		'sellerId',
		'createdFor',
		'validUntil',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];
}