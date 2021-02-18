<?php

namespace App\Models;

class CustomerWishlist extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'customer-wishlist';
	protected $fillable = [
		'customerId',
		'productId',
	];
	protected $hidden = [
		'id',
		'customerId',
		'created_at',
		'updated_at',
	];
}