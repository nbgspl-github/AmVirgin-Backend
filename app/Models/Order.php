<?php

namespace App\Models;

use App\Classes\Str;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\Customer;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use App\Models\SellerOrder;

class Order extends Model{
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
		'status',
	];
	protected $hidden = ['created_at', 'updated_at'];

	public static $status = [
		'Placed' => 'placed',
		'Dispatched' => 'dispatched',
		'Delivered' => 'delivered',
		'Cancelled' => 'cancelled',
		'RefundProcessing' => 'refund-processing',
		'OutForDelivery' => 'out-for-delivery',
	];

	public function setOrderNumberAttribute($value){
		$this->attributes['orderNumber'] = sprintf('AVG-%d-%d', time(), rand(1, 1000));
	}

	public function items(){
		return $this->hasMany('App\Models\OrderItem', 'orderId')->with('productDetails');
	}

	public function customer(){
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public function products(){
		return $this->belongsTo(Product::class, OrderItem::class, 'id', 'productId');
	}

	public function address(){
		return $this->belongsTo(ShippingAddress::class, 'addressId')->with('city', 'state');
	}

	public static function getAllStatus(){
		return self::$status;
	}

	public function save(array $options = []){
		$this->orderNumber = Str::Empty;
		return parent::save($options);
	}
}