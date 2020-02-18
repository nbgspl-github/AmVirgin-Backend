<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model{
	protected $table = 'carts';
	protected $fillable = [
		'sessionId',
		'addressId',
		'customerId',
		'itemCount',
		'subTotal',
		'tax',
		'paymentMode',
		'status',
	];

	public function items(){
		return $this->hasMany(CartItem::class, 'cartId');
	}
}