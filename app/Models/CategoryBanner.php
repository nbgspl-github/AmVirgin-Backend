<?php
namespace App\Models;
use App\Traits\GenerateUrls;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryBanner extends Model {
	use RetrieveResource;
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
