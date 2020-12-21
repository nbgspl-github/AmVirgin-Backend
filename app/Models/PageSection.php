<?php

namespace App\Models;

use App\Library\Enums\Common\PageSectionType;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
	use DynamicAttributeNamedMethods;

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
	public const Type = [
		'Entertainment' => 'entertainment',
		'Shop' => 'shop',
	];

	public static function entertainment () : Builder
	{
		return self::where('type', PageSectionType::Entertainment);
	}

	public static function shop () : Builder
	{
		return self::where('type', PageSectionType::Shop);
	}
}