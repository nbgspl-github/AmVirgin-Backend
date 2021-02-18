<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;

class AttributeValue extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'attribute-values';
	protected $fillable = ['attributeId', 'categoryId', 'value'];
}