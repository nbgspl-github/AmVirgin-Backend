<?php

namespace App\Models;

use App\Models\Auth\Customer;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
	use DynamicAttributeNamedMethods, RetrieveResource;

	protected $guarded = [
		'id'
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public function items ()
	{
		return $this->hasMany(OrderItem::class, 'orderId')->with('product');
	}

	public function customer ()
	{
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public function products ()
	{
		return $this->belongsTo(Product::class, OrderItem::class, 'id', 'productId');
	}

	public function address ()
	{
		return $this->belongsTo(ShippingAddress::class, 'addressId')->with('city', 'state');
	}

	public function sellerOrder (): HasOne
	{
		return $this->hasOne(SellerOrder::class, 'orderId', 'id');
	}

	public function transaction (): HasOne
	{
		return $this->hasOne(Transaction::class, 'orderId');
	}

	public function segments (): HasMany
	{
		return $this->hasMany(OrderSegment::class, 'orderId');
	}

	public function timeline (): HasMany
	{
		return $this->hasMany(OrderTimeline::class, 'orderId');
	}
}