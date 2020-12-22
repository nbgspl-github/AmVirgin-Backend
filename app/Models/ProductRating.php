<?php

namespace App\Models;

use App\Models\Auth\Customer;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRating extends \App\Library\Database\Eloquent\Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'date', 'status'];
	protected $guarded = ['id'];

	public function customer () : BelongsTo
	{
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public function order () : BelongsTo
	{
		return $this->belongsTo(Order::class, 'orderId')->with('address');
	}
}