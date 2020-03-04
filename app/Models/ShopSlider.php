<?php

namespace App\Models;

use App\Traits\ActiveStatus;
use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class ShopSlider extends Model {
	use ActiveStatus;
	use RetrieveResource;
	use HasAttributeMethods;
	protected $table = 'shop-sliders';
	protected $fillable = [
		'title',
		'description',
		'banner',
		'target',
		'rating',
		'active',
	];
	protected $hidden = [
		'id',
		'created_at',
		'updated_at',
	];
}