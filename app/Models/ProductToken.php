<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class ProductToken extends Model{
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