<?php

namespace App\Models;

use App\Traits\HasAttributeMethods;
use Illuminate\Database\Eloquent\Model;

class HsnCode extends Model {
	use HasAttributeMethods;
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
