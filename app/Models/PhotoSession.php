<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhotoSession extends Model
{
    protected $table = 'photo_sessions';

    protected $fillable = [
        'session_id',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(
            PhotoResult::class,
            'session_id',
            'id'
        );
    }
}