<?php

namespace App\Models;

use App\Queries\AnnouncementQuery;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model{
	use DynamicAttributeNamedMethods;

	protected $fillable = [
		'title',
		'content',
		'banner',
		'validFrom',
		'validUntil',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public static function startQuery(): AnnouncementQuery{
		return AnnouncementQuery::begin();
	}
}
