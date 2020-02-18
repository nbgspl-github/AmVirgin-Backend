<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model{
	protected $table = 'cart-items';
	protected $fillable = [
		'cartId',
		'productId',
		'uniqueId',
		'quantity',
		'',
	];
}