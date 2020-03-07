<?php

namespace App\Models;

use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model {
	use RetrieveResource, RetrieveCollection, HasAttributeMethods;
	protected $table = 'page-sections';
	protected $fillable = [
		'title',
		'type',
		'visibleItemCount',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];
}