<?php

namespace App\Models;

use App\Storage\SecuredDisk;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
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
}
