<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;

class SellerOrderItem extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $guarded = [
		'id'
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public function productDetails ()
	{
		return $this->hasOne(Product::class, 'id', 'productId')->with('images');
	}

	public function product ()
	{
		return $this->hasOne(Product::class, 'id', 'productId');
	}
}