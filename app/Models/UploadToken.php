<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadToken extends Model{
	protected $table = 'upload-tokens';
	protected $fillable = [
		'token',
		'key',
		'model',
	];
}
