<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{
	use FluentConstructor;

	protected $table = 'products';

	protected $fillable = [

	];

	protected $hidden = [
		'created_at',
		'updated_at',
	];
}
