<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWishlist extends Model {
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