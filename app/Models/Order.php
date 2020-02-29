<?php

namespace App\Models;

use App\Classes\Str;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
	protected $table = 'orders';

	public function setOrderNumberAttribute($value) {
		$this->attributes['orderNumber'] = sprintf('AVG-%d-%d', time(), $this->getKey());
	}

	public function items() {
		return $this->hasMany('App\Models\OrderItem', 'orderId');
	}

	public function save(array $options = []) {
		$this->orderNumber = Str::Empty;
		return parent::save($options);
	}
}