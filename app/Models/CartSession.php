<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartSession extends Model {
	protected string $table = 'cart-sessions';
	protected array $fillable = [
		'sessionId',
		'customerId',
	];
	protected array $hidden = [
		'id',
		'created_at',
		'updated_at',
	];
}
