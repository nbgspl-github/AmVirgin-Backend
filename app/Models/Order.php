<?php

namespace App\Models;

use App\Enums\Orders\Status;
use App\Models\Auth\Customer;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Order
 * @package App\Models
 * @property Status $status
 */
class Order extends Model
{
	use DynamicAttributeNamedMethods, RetrieveResource;

	protected $guarded = ['id'];
	protected $casts = ['status' => Status::class];
	protected $hidden = ['created_at', 'updated_at'];

	public function items (): HasMany
	{
		return $this->hasMany(OrderItem::class, 'orderId')->with('product');
	}

	public function customer (): BelongsTo
	{
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public function products (): BelongsTo
	{
		return $this->belongsTo(Product::class, OrderItem::class, 'id', 'productId');
	}

	public function address (): BelongsTo
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

	public function subOrders (): HasMany
	{
		return $this->hasMany(SubOrder::class, 'orderId');
	}

	public function timeline (): HasMany
	{
		return $this->hasMany(OrderTimeline::class, 'orderId');
	}
}