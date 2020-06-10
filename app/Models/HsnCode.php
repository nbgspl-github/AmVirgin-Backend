<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class HsnCode extends Model{
	use DynamicAttributeNamedMethods;
	protected $table = 'hsn-codes';
	protected $primaryKey = 'hsnCode';
	protected $fillable = [
		'hsnCode',
		'taxRate',
	];
	public $timestamps = false;
	protected $casts = [
		'hsnCode' => 'integer',
		'taxRate' => 'integer',
	];
}
