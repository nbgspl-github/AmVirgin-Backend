<?php

namespace App\Models\Video;

use App\Models\Auth\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stats extends \App\Library\Database\Eloquent\Model
{
    protected $table = 'video_stats';

    public function customer (): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function video (): BelongsTo
    {
        return $this->belongsTo(Video::class, 'video_id');
    }

    public function records (): HasMany
    {
        return $this->hasMany(Stats::class, 'customer_id');
    }
}
