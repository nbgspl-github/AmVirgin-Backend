<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model{
	protected $table = 'attribute-values';
	protected $fillable = ['attributeId', 'value'];
}
