<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class PrimitiveType extends Model{
	use DynamicAttributeNamedMethods;
	protected $table = 'primitive-types';
	protected $primaryKey = 'typeCode';
	protected $fillable = ['typeCode', 'primitiveType', 'usableFunction', 'measurable'];
	protected $casts = ['measurable' => 'bool'];
	public $incrementing = false;
	public $timestamps = false;
}