<?php

namespace App\Models;

class CustomerRecent extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'customer-recent';
	protected $fillable = [
		'customerId',
		'type',
		'key',
	];
}