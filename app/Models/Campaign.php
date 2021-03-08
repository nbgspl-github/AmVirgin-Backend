<?php

namespace App\Models;

use App\Library\Enums\News\Article\Types;
use BenSampo\Enum\Traits\CastsEnums;

/**
 * Class Campaign
 * @package App\Models
 * @property Types $type
 */
class Campaign extends \App\Library\Database\Eloquent\Model
{
    use CastsEnums;

    protected $table = 'campaigns';

    protected $casts = [
        'type' => Types::class
    ];

    public function setThumbnailAttribute ($value)
    {
        $this->attributes['thumbnail'] = $this->storeWhenUploadedCorrectly('campaigns/thumbnails', $value);
    }

    public function getThumbnailAttribute ($value): ?string
    {
        return $this->retrieveMedia($value);
    }

    public function setVideoAttribute ($value)
    {
        $this->attributes['video'] = $this->storeWhenUploadedCorrectly('campaigns/videos', $value);
    }

    public function getVideoAttribute ($value): ?string
    {
        return $this->retrieveMedia($value);
    }
}