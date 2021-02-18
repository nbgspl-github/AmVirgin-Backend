<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends \App\Library\Database\Eloquent\Model
{
	public function subOrder () : HasOne
	{
		return $this->hasOne(SubOrder::class, 'shipmentId', 'id');
	}
}