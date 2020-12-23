<?php

namespace App\Models\Cart;

class Session extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'cart_sessions';
	protected $hidden = [
		'id',
		'created_at',
		'updated_at',
	];
}