<?php

namespace App\Models;

use App\Classes\Str;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ShippingAddress;

class Order extends Model {
	protected $table = 'orders';
	protected $fillable = [
		'customerId',
		'addressId',
		'orderNumber',
		'quantity',
		'subTotal',
		'tax',
		'total',
		'paymentMode',
		'status',
	];

	public function setOrderNumberAttribute($value) {
		$this->attributes['orderNumber'] = sprintf('AVG-%d-%d', time(), rand(1, 1000));
	}

	public function items() {
		return $this->hasMany('App\Models\OrderItem', 'orderId')->with('productDetails');
	}
	public function customer() {
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public function products() {
		return $this->belongsTo(Product::class,OrderItem::class,'id', 'productId');
	}

	public function address() {
		return $this->belongsTo(ShippingAddress::class, 'addressId')->with('city','state');
	}


	public function save(array $options = []) {
		$this->orderNumber = Str::Empty;
		return parent::save($options);
	}
}