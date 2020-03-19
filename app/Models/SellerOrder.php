<?php

namespace App\Models;

use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Order;

class SellerOrder extends Model {
	use HasAttributeMethods, RetrieveResource, RetrieveCollection;
	protected $table = 'seller-orders';
	protected $fillable = [
		'sellerId',
		'customerId',
		'orderId',
		'orderNumber',
	];

	public function seller() {
		return $this->belongsTo('App\Models\Seller', 'sellerId');
	}
	public function customer() {
		return $this->belongsTo(Customer::class, 'customerId');
	}


	public function items() {
		return $this->hasMany('App\Models\SellerOrderItem', 'sellerOrderId');
	}

	public function item() {
		return $this->hasMany('App\Models\SellerOrderItem', 'sellerOrderId')->with('productDetails');
	}

	public function order() {
		return $this->belongsTo(Order::class, 'orderId')->with('address');
	}
}
