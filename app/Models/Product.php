<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'brand',
        'manufacturer',
        'pack_size',
        'dosage_form',
        'rx_required',
        'price',
        'mrp',
        'rating',
        'review_count',
        'art_style',
        'details',
        'featured',
        'stock',
        'image_url',
        'image_path',
        'video_path',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'mrp' => 'decimal:2',
            'rating' => 'decimal:1',
            'details' => 'array',
            'rx_required' => 'boolean',
            'featured' => 'boolean',
            'active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
