<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model{
	use FluentConstructor, RetrieveResource, HasAttributeMethods;

	protected $table = 'attributes';
	protected $fillable = [
		'name',
		'categoryId',
		'code',
		'sellerInterfaceType',
		'customerInterfaceType',
		'code',
		'genericType',
		'required',
		'filterable',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public function values(){
		return $this->hasMany('App\Models\AttributeValue', 'attributeId');
	}

	public function category(){
		return $this->belongsTo(Category::class, 'categoryId');
	}
}
