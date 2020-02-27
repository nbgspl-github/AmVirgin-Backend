<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model {
	protected $table = 'states';
	protected $hidden = [
		'id',
		'created_at',
		'updated_at',
		'countryId',
	];
}