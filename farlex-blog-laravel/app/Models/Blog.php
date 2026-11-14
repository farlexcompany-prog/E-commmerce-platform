<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';

    // The upload tool manages created_at itself; the table has no updated_at.
    public $timestamps = false;

    protected $casts = [
        'created_at'   => 'datetime',
        'published_at' => 'datetime',
        'publish_date' => 'datetime',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function getShownDateAttribute()
    {
        return $this->published_at ?? $this->created_at;
    }
}
