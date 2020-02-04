<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartSession extends Model{
	protected $table = 'cart-sessions';
	protected $fillable = [
		'sessionId',
		'customerId',
	];
}
