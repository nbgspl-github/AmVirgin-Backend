<?php

namespace App\Models;

use App\Traits\HasAttributeMethods;
use Illuminate\Database\Eloquent\Model;

class PrimitiveType extends Model{
	use HasAttributeMethods;
	protected $table = 'primitive-types';
	protected $primaryKey = 'typeCode';
	protected $fillable = ['typeCode', 'primitiveType', 'usableFunction', 'measurable'];
	protected $casts = ['measurable' => 'bool'];
	public $incrementing = false;
	public $timestamps = false;
}