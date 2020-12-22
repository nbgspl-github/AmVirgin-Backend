<?php

namespace App\Models\Cart;

class Session extends \App\Library\Database\Eloquent\Model
{
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