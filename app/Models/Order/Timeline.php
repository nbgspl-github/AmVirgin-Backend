<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timeline extends \App\Library\Database\Eloquent\Model
{
	public function order () : BelongsTo
	{
		return $this->belongsTo(Order::class, 'orderId');
	}
}