<?php

namespace App\Models;

use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

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

	public function items() {
		return $this->hasMany('App\Models\SellerOrderItem', 'sellerOrderId');
	}
}
