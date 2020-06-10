<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model{
	use DynamicAttributeNamedMethods, RetrieveResource;
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

	public function product(){
		return $this->hasOne(Product::class, 'id', 'productId')->with('images');
	}
}