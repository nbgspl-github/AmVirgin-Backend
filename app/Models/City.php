<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;

class City extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'cities';
	protected $hidden = [
		'created_at',
		'updated_at',
	];
}