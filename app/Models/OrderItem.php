<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
	protected $table = 'order-items';
	protected $fillable = [
		'orderId',
		'productId',
		'quantity',
		'price',
		'total',
		'options',
	];
	protected $casts = [
		'options' => 'array',
	];

	public function attributes() {

	}
}