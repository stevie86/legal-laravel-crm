<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'counseling_session_id',
        'title',
        'description',
        'original_filename',
        'stored_filename',
        'file_path',
        'mime_type',
        'file_size',
        'document_type',
        'is_confidential',
        'tags',
    ];

    protected $casts = [
        'is_confidential' => 'boolean',
        'tags' => 'array',
    ];

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('documents.download', $this->id);
    }

    // Beziehungen
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function counselingSession(): BelongsTo
    {
        return $this->belongsTo(CounselingSession::class);
    }
}
