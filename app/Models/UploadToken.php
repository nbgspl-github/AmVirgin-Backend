<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadToken extends Model {
	protected string $table = 'upload-tokens';
	protected array $fillable = [
		'token',
		'key',
		'model',
	];
}
