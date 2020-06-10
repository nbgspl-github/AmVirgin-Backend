<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class State extends Model{
	use DynamicAttributeNamedMethods;
	protected $table = 'states';
	protected $fillable = ['id', 'name', 'countryId'];
	protected $hidden = [
		'created_at',
		'updated_at',
	];
}