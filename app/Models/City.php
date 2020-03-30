<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model{
	protected $table = 'cities';
	protected $hidden = [
		'created_at',
		'updated_at',
	];
}