<?php

namespace App\Models;

use App\Queries\AnnouncementQuery;
use App\Traits\DynamicAttributeNamedMethods;

class Announcement extends \App\Library\Database\Eloquent\Model
{
    use DynamicAttributeNamedMethods;

    protected $casts = [
        'readBy' => 'array',
        'deletedBy' => 'array',
    ];

    public function setBannerAttribute ($value): void
    {
        $this->attributes['banner'] = $this->storeWhenUploadedCorrectly('announcements\banner', $value);
    }

    public function getBannerAttribute ($value): ?string
    {
        return $this->retrieveMedia($value);
    }

    public static function startQuery (): AnnouncementQuery
    {
        return AnnouncementQuery::begin();
    }
}
