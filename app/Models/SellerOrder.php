<?php

namespace App\Models;

use App\Models\Auth\Customer;
use App\Models\Auth\Seller;
use App\Queries\SellerOrderQuery;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class SellerOrder extends Model
{
	use DynamicAttributeNamedMethods;

	protected $guarded = [
		'id'
	];

	protected $fillable = [
		'placedOn',
		'dispatchedOn',
		'shippedOn'
	];

	public function seller ()
	{
		return $this->belongsTo(Seller::class, 'sellerId');
	}

	public function customer ()
	{
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public function items ()
	{
		return $this->hasMany(SellerOrderItem::class, 'sellerOrderId');
	}

	public function item ()
	{
		return $this->hasMany(SellerOrderItem::class, 'sellerOrderId')->with('productDetails');
	}

	public function order ()
	{
		return $this->belongsTo(Order::class, 'orderId')->with('address');
	}

	public function sellerBank ()
	{
		return $this->belongsTo(SellerBankDetail::class, 'sellerId');
	}

	public static function startQuery () : SellerOrderQuery
	{
		return SellerOrderQuery::begin();
	}
}