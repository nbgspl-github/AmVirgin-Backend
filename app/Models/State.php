<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model{
	protected $table = 'states';
	protected $fillable = ['id', 'name', 'countryId'];
	protected $hidden = [
		'created_at',
		'updated_at',
	];
}