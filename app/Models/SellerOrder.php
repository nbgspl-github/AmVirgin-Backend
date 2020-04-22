<?php

namespace App\Models;

use App\Queries\SellerOrderQuery;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\Customer;
use App\Models\Order;

class SellerOrder extends Model{
	use DynamicAttributeNamedMethods, RetrieveResource, RetrieveCollection;

	protected $table = 'seller-orders';
	protected $fillable = [
		'sellerId',
		'customerId',
		'orderId',
		'orderNumber',
	];

	public function seller(){
		return $this->belongsTo('App\Models\Auth\Seller', 'sellerId');
	}

	public function customer(){
		return $this->belongsTo(Auth\Customer::class, 'customerId');
	}

	public function items(){
		return $this->hasMany('App\Models\SellerOrderItem', 'sellerOrderId');
	}

	public function item(){
		return $this->hasMany('App\Models\SellerOrderItem', 'sellerOrderId')->with('productDetails');
	}

	public function order(){
		return $this->belongsTo(Order::class, 'orderId')->with('address');
	}

	public static function startQuery(): SellerOrderQuery{
		return SellerOrderQuery::begin();
	}
}
