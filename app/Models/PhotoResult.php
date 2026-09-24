<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoResult extends Model
{
    protected $table = 'photo_results';

    protected $fillable = [
        'filename',
        'url',
        'session_id',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(
            PhotoSession::class,
            'session_id',
            'id'
        );
    }
}