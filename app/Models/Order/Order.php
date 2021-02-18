<?php

namespace App\Models\Order;

use App\Library\Enums\Orders\Payments\Methods;
use App\Library\Enums\Orders\Status;
use App\Models\Auth\Customer;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Represents the top level order element of an order placed by a customer.
 * @package App\Models\Order
 * @property Status $status
 * @property ?Customer $customer
 * @property ?\App\Models\Address\Address $address
 * @property ?\App\Models\Address\Address $billingAddress
 * @property Methods $paymentMode
 */
class Order extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods, \BenSampo\Enum\Traits\CastsEnums;

	protected $casts = ['status' => Status::class, 'paymentMode' => Methods::class];

	protected static function boot ()
	{
		parent::boot();
		self::creating(function ($order) {
			$prefix = 'AVG';
			$major = date('Ymd');
			$minor = date('His');
			$suffix = mt_rand(499, 999);
			$order->orderNumber = ("{$major}-{$minor}-{$suffix}");
		});
	}

	public function items () : HasMany
	{
		return $this->hasMany(\App\Models\Order\Item::class, 'orderId')->with('product');
	}

	public function customer () : BelongsTo
	{
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public function products () : BelongsTo
	{
		return $this->belongsTo(\App\Models\Product::class, \App\Models\Order\Item::class, 'id', 'productId');
	}

	public function address () : BelongsTo
	{
		return $this->belongsTo(\App\Models\Address\Address::class, 'addressId')->with('city', 'state')->withTrashed();
	}

	public function billingAddress () : BelongsTo
	{
		return $this->belongsTo(\App\Models\Address\Address::class, 'billingAddressId')->with('city', 'state')->withTrashed();
	}

	public function sellerOrder () : HasOne
	{
		return $this->hasOne(SubOrder::class, 'orderId', 'id');
	}

	public function transaction () : HasOne
	{
		return $this->hasOne(\App\Models\Order\Transaction::class, 'orderId');
	}

	public function subOrders () : HasMany
	{
		return $this->hasMany(\App\Models\Order\SubOrder::class, 'orderId');
	}

	public function timeline () : HasMany
	{
		return $this->hasMany(\App\Models\Order\Timeline::class, 'orderId');
	}
}