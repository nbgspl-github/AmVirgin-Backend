<?php

namespace App\Models;

use App\Enums\Orders\Status;
use App\Models\Auth\Customer;
use App\Models\Auth\Seller;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SubOrder
 * @package App\Models
 * @property ?Order $order
 * @property ?Seller $seller
 * @property Status $status
 */
class SubOrder extends Model
{
	protected $guarded = ['id'];

	protected $casts = ['status' => Status::class];

	public function items (): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(OrderItem::class, 'subOrderId');
	}

	public function customer (): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Customer::class, 'customerId');
	}
}