<?php

namespace App\Models;

use App\Traits\GenerateUrls;

class CategoryBanner extends \App\Library\Database\Eloquent\Model
{
	use GenerateUrls;

	protected $table = 'category-banner';

	protected $fillable = [
		'title',
		'order',
		'image',
		'status',
		'sectionTitle',
		'layoutType',
		'validFrom',
		'validUntil',
		'hasValidity',
	];

	protected $hidden = [
		'deleted',
		'created_at',
		'updated_at',
		'id',
	];
}