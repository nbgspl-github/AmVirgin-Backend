<?php

namespace App\Models;

use App\Library\Enums\Orders\Payments\Methods;
use App\Library\Enums\Orders\Status;
use App\Models\Auth\Customer;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Represents the top level order element of an order placed by a customer.
 * @package App\Models
 * @property Status $status
 * @property ?Customer $customer
 * @property ?Address $address
 * @property ?Address $billingAddress
 * @property Methods $paymentMode
 */
class Order extends \App\Library\Extensions\ModelExtended
{
	use DynamicAttributeNamedMethods;

	protected $guarded = ['id'];
	protected $casts = ['status' => Status::class, 'paymentMode' => Methods::class];
	protected $hidden = ['created_at', 'updated_at'];

	protected static function boot ()
	{
		parent::boot();
		self::creating(function ($order) {
			$prefix = 'AVG';
			$major = date('Ymd');
			$minor = date('His');
			$suffix = mt_rand(100, 999);
			$order->orderNumber = ("{$prefix}-{$major}-{$minor}-{$suffix}");
		});
	}

	public function items () : HasMany
	{
		return $this->hasMany(OrderItem::class, 'orderId')->with('product');
	}

	public function customer () : BelongsTo
	{
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public function products () : BelongsTo
	{
		return $this->belongsTo(Product::class, OrderItem::class, 'id', 'productId');
	}

	public function address () : BelongsTo
	{
		return $this->belongsTo(Address::class, 'addressId')->with('city', 'state');
	}

	public function billingAddress () : BelongsTo
	{
		return $this->belongsTo(Address::class, 'billingAddressId')->with('city', 'state');
	}

	public function sellerOrder () : HasOne
	{
		return $this->hasOne(SellerOrder::class, 'orderId', 'id');
	}

	public function transaction () : HasOne
	{
		return $this->hasOne(Transaction::class, 'orderId');
	}

	public function subOrders () : HasMany
	{
		return $this->hasMany(SubOrder::class, 'orderId');
	}

	public function timeline () : HasMany
	{
		return $this->hasMany(OrderTimeline::class, 'orderId');
	}
}