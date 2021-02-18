<?php

namespace App\Models;

class SellerPayment extends \App\Library\Database\Eloquent\Model
{
	public function order () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(\App\Models\Order\SubOrder::class, 'sub_order_id');
	}

	public function seller () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(\App\Models\Auth\Seller::class, 'seller_id');
	}
}