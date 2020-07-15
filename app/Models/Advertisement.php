<?php

namespace App\Models;

use App\Queries\Seller\AdvertisementQuery;
use App\Storage\SecuredDisk;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\FluentConstructor;
use App\Traits\GenerateSlugs;
use App\Traits\HasSpecialAttributes;
use App\Traits\QueryProvider;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use RetrieveResource, FluentConstructor, DynamicAttributeNamedMethods, QueryProvider;
    protected $fillable = [
        'sellerId', 'subject', 'message', 'banner', 'date', 'active'
    ];
    protected $hidden = [
        'id', 'created_at', 'updated_at'
    ];
    protected $casts = [
        'active' => 'bool'
    ];

    public function getBannerAttribute($value): ?string
    {
        return SecuredDisk::existsUrl($this->attributes['banner']);
    }

    public static function startQuery(): AdvertisementQuery
    {
        return AdvertisementQuery::begin();
    }
}