<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;

class CustomerQuery extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'customer-queries';
	protected $fillable = [
		'customerId',
		'name',
		'email',
		'mobile',
		'query',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];
}