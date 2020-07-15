<?php

namespace App\Models;

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
}
