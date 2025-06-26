<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CounselingSession extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'title',
        'description',
        'scheduled_at',
        'duration_minutes',
        'status',
        'session_type',
        'location',
        'is_online',
        'online_meeting_link',
        'fee',
        'fee_paid',
        'cancellation_reason',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_online' => 'boolean',
        'fee' => 'decimal:2',
        'fee_paid' => 'boolean',
    ];

    public function getEndTimeAttribute()
    {
        return $this->scheduled_at->addMinutes($this->duration_minutes);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'badge-primary',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            'no_show' => 'badge-warning',
            default => 'badge-secondary'
        };
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

    public function sessionNotes(): HasMany
    {
        return $this->hasMany(SessionNote::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function calendarEvent(): HasOne
    {
        return $this->hasOne(CalendarEvent::class);
    }
}
