<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrameTemplate extends Model
{
    protected $table = 'frame_templates';

    protected $fillable = [
        "name",
        'file_path',
        'file_url',
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