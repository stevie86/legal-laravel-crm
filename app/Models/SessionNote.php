<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionNote extends Model
{
    protected $fillable = [
        'counseling_session_id',
        'user_id',
        'title',
        'content',
        'note_type',
        'is_confidential',
        'is_template',
        'tags',
    ];

    protected $casts = [
        'is_confidential' => 'boolean',
        'is_template' => 'boolean',
        'tags' => 'array',
    ];

    public function getExcerptAttribute(): string
    {
        return \Str::limit(strip_tags($this->content), 100);
    }

    // Beziehungen
    public function counselingSession(): BelongsTo
    {
        return $this->belongsTo(CounselingSession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
