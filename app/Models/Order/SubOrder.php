<?php

namespace App\Models\Order;

use App\Library\Enums\Orders\Status;
use App\Models\Auth\Customer;
use App\Models\Auth\Seller;
use App\Queries\SubOrderQuery;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SubOrder
 * @package App\Models\Order
 * @property ?Order $order
 * @property ?Seller $seller
 * @property Status $status
 * @property Arrayable|Item[] $items|Illuminate\Support\Collection
 */
class SubOrder extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'sub_orders';

	protected $casts = ['status' => Status::class];

	protected static function boot ()
	{
		parent::boot();
		self::creating(function (SubOrder $order) {
			$major = date('Ymd');
			$minor = date('His');
			$suffix = 100 + Order::query()->whereKey($order->orderId)->count('id');
			$order->orderNumber = ("{$major}-{$minor}-{$suffix}");
		});
	}

	/*<--Relationships & Query builders-->*/

	public function items () : HasMany
	{
		return $this->hasMany(Item::class, 'subOrderId');
	}

	public function customer () : BelongsTo
	{
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public static function startQuery () : SubOrderQuery
	{
		return SubOrderQuery::begin();
	}

	public function seller () : BelongsTo
	{
		return $this->belongsTo(Seller::class, 'sellerId');
	}

	public function shipment () : BelongsTo
	{
		return $this->belongsTo(\App\Models\Order\Shipment::class, 'shipmentId', 'id');
	}

	public function order () : BelongsTo
	{
		return $this->belongsTo(Order::class, 'orderId');
	}

	public function products () : \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(\App\Models\Product::class, 'order_items', 'subOrderId', 'productId')->withPivot('quantity');
	}

	public function payments () : HasMany
	{
		return $this->hasMany(\App\Models\SellerPayment::class, 'sub_order_id');
	}

	/*<--Instance Methods-->*/

	public function description () : string
	{
		$products = $this->products->transform(function (\App\Models\Product $product) {
			return "{$product->pivot->quantity} x {$product->name}";
		});
		return \App\Library\Utils\Extensions\Str::join(',', $products->toArray());
	}

	public function courierCharge () : float
	{
		return $this->items->sum(fn (
			Item $item) => $item->product != null ? $item->product->shippingCost() : 0.0
		);
	}

	public function sellingFee () : float
	{
		return (0.2 * $this->total);
	}

	public function grossTotal () : float
	{
		$sellingFee = $this->sellingFee();
		$shippingCost = $this->courierCharge();
		return (
			$this->total - ($sellingFee + $shippingCost)
		);
	}
}