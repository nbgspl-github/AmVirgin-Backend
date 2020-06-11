<?php

namespace App\Models;

use App\Models\Auth\Customer;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
	use DynamicAttributeNamedMethods, RetrieveResource;

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
		'transactionId',
		'status',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public static $status = [
		'Placed' => 'placed',
		'ReadyForDispatch' => 'ready-for-dispatch',
		'Dispatched' => 'dispatched',
		'Delivered' => 'delivered',
		'Cancelled' => 'cancelled',
		'RefundProcessing' => 'refund-processing',
		'OutForDelivery' => 'out-for-delivery',
	];

	public function items () {
		return $this->hasMany(OrderItem::class, 'orderId')->with('product');
	}

	public function customer () {
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public function products () {
		return $this->belongsTo(Product::class, OrderItem::class, 'id', 'productId');
	}

	public function address () {
		return $this->belongsTo(ShippingAddress::class, 'addressId')->with('city', 'state');
	}

	public static function getAllStatus () {
		return self::$status;
	}
}