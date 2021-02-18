<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;

class State extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'states';
	protected $fillable = ['id', 'name', 'countryId'];
	protected $hidden = [
		'created_at',
		'updated_at',
	];
}