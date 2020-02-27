<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model {
	protected $table = 'shipping-addresses';

	public function state() {
		return $this->belongsTo('App\Models\State', 'stateId');
	}

	public function city() {
		return $this->belongsTo('App\Models\City', 'cityId');
	}
}
