<?php

namespace App\Models;

use App\Queries\AnnouncementQuery;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model{
	use DynamicAttributeNamedMethods;

	protected $attributes = [
		'readBy' => [],
		'deletedBy' => [],
	];
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
	protected $casts = [
		'readBy' => 'array',
		'deletedBy' => 'array',
	];

	public static function startQuery(): AnnouncementQuery{
		return AnnouncementQuery::begin();
	}
}
