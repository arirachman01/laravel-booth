<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'eyebrow',
        'website_name',
        'tagline',
        'website_description',
        'photo_price',
        'photo_count',
        'countdown',
        'max_time',
        'primary_color',
        'secondary_color',
        'background_color',
        'surface_color',
        'text_color',
        'accent_color',
    ];

    protected $casts = [
        'photo_price' => 'integer',
        'photo_count' => 'integer',
        'countdown' => 'integer',
        'max_time' => 'integer',
    ];
}