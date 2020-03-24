<?php

namespace App\Models;

use App\Classes\Str;
use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model{
	use HasAttributeMethods, RetrieveResource;
	protected $table = 'brands';
	protected $fillable = ['name', 'slug', 'logo', 'active'];
	protected $casts = [
		'active' => 'bool',
	];
	protected $hidden=[
		'created_at','updated_at','id'
	];

	public function setNameAttribute($value){
		$this->attributes['name'] = $value;
		$this->attributes['slug'] = Str::slug($value);
	}
}