<?php

namespace App\Models;

use App\Library\Enums\Orders\Status;
use App\Models\Auth\Customer;
use App\Models\Auth\Seller;
use App\Models\Order\Item;
use App\Models\Order\Order;
use App\Queries\SubOrderQuery;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SubOrder
 * @package App\Models
 * @property ?Order $order
 * @property ?Seller $seller
 * @property Status $status
 * @property Arrayable|Item[] $items
 */
class SubOrder extends \App\Library\Database\Eloquent\Model
{
	protected $guarded = ['id'];

	protected $casts = ['status' => Status::class];

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
		return $this->belongsTo(Shipment::class, 'shipmentId', 'id');
	}

	public function order () : BelongsTo
	{
		return $this->belongsTo(Order::class, 'orderId');
	}
}