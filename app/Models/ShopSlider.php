<?php

namespace App\Models;

use App\Traits\ActiveStatus;
use Illuminate\Database\Eloquent\Model;

class ShopSlider extends Model {
	use ActiveStatus;
	protected $table = 'shop-sliders';
	protected $fillable = [
		'title',
		'description',
		'banner',
		'target',
		'stars',
		'active',
	];
	protected $hidden = [
		'id',
		'created_at',
		'updated_at',
	];
}