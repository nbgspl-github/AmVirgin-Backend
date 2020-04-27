<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class CustomerQuery extends Model{
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