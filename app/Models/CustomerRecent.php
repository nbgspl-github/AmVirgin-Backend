<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRecent extends Model {
	protected $table = 'customer-recent';
	protected $fillable = [
		'customerId',
		'type',
		'key',
	];
}
