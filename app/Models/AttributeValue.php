<?php

namespace App\Models;

use App\Traits\HasAttributeMethods;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model{
	use HasAttributeMethods;
	protected $table = 'attribute-values';
	protected $fillable = ['attributeId', 'categoryId', 'value'];
}
