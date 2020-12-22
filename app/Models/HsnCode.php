<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;

class HsnCode extends \App\Library\Database\Eloquent\Model
{
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