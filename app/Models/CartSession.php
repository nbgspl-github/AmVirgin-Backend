<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartSession extends Model {
	protected $table = 'cart-sessions';
	protected $fillable = [
		'sessionId',
		'customerId',
	];
	protected $hidden = [
		'id',
		'created_at',
		'updated_at',
	];
}
