<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCart extends \App\Library\Database\Eloquent\Model
{

	protected $table = 'cart';

	protected $fillable = [
		'cartId',
		'productId',
		'quantity',
		'customerId',
		'status',
	];

	protected $hidden = [
		'deleted',
		'created_at',
		'updated_at',

	];

	/**
	 * @return HasMany
	 */
	public function images ()
	{
		return $this->hasMany(ProductImage::class, 'productId');
	}


}