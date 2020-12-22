<?php

namespace App\Models;

class UploadToken extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'upload-tokens';
	protected $fillable = [
		'token',
		'key',
		'model',
	];
}