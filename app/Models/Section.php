<?php

namespace App\Models;

use App\Library\Enums\Common\PageSectionType;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Builder;

class Section extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'video_sections';

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