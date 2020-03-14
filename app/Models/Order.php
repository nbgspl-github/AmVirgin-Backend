<?php

namespace App\Models;

use App\Classes\Str;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class Order extends Model{
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

	public function setOrderNumberAttribute($value){
		$this->attributes['orderNumber'] = sprintf('AVG-%d-%d', time(), rand(1, 1000));
	}

	public function items(){
		return $this->hasMany('App\Models\OrderItem', 'orderId');
	}

	public function customer(){
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public function save(array $options = []){
		$this->orderNumber = Str::Empty;
		return parent::save($options);
	}
}