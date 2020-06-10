<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class City extends Model{
	use DynamicAttributeNamedMethods;
	protected $table = 'cities';
	protected $hidden = [
		'created_at',
		'updated_at',
	];
}