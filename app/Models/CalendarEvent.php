<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEvent extends Model
{
    protected $fillable = [
        'user_id',
        'client_id',
        'counseling_session_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'all_day',
        'event_type',
        'location',
        'color',
        'is_recurring',
        'recurrence_rules',
        'reminder_at',
        'reminder_sent',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'all_day' => 'boolean',
        'is_recurring' => 'boolean',
        'recurrence_rules' => 'array',
        'reminder_at' => 'datetime',
        'reminder_sent' => 'boolean',
    ];

    public function getDurationAttribute(): int
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }

    public function getFormattedDateTimeAttribute(): string
    {
        if ($this->all_day) {
            return $this->start_time->format('d.m.Y');
        }

        return $this->start_time->format('d.m.Y H:i').' - '.$this->end_time->format('H:i');
    }

    // Beziehungen
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function counselingSession(): BelongsTo
    {
        return $this->belongsTo(CounselingSession::class);
    }
}
