<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends Model
{
	protected $guarded = ['id'];

	public function subOrder () : HasOne
	{
		return $this->hasOne(SubOrder::class, 'shipmentId', 'id');
	}
}