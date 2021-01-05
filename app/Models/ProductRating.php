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

	protected $casts = ['certified' => 'bool'];

	public function customer () : BelongsTo
	{
		return $this->belongsTo(Customer::class, 'customer_id');
	}

	public function order () : BelongsTo
	{
		return $this->belongsTo(Order::class, 'order_id')->with('address');
	}

	public function images () : \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(\App\Models\Product\Image::class, 'product_rating_id');
	}
}