<?php

namespace App\Models;

use App\Constants\PageSectionType;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model{
	use RetrieveResource, RetrieveCollection, DynamicAttributeNamedMethods;
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

	public static function entertainment(): Builder{
		return self::where('type', PageSectionType::Entertainment);
	}

	public static function shop(): Builder{
		return self::where('type', PageSectionType::Shop);
	}
}