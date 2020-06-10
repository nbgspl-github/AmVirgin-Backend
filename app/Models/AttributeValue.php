<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model{
	use DynamicAttributeNamedMethods;
	protected $table = 'attribute-values';
	protected $fillable = ['attributeId', 'categoryId', 'value'];
}
