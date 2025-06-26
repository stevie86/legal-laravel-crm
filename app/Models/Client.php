<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'birth_date',
        'gender',
        'address',
        'postal_code',
        'city',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_notes',
        'general_notes',
        'status',
        'client_number',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($client) {
            if (empty($client->client_number)) {
                $client->client_number = 'CL' . str_pad(
                    (static::max('id') ?? 0) + 1, 
                    6, 
                    '0', 
                    STR_PAD_LEFT
                );
            }
        });
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    // Beziehungen
    public function counselingSessions(): HasMany
    {
        return $this->hasMany(CounselingSession::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function relationships(): HasMany
    {
        return $this->hasMany(ClientRelationship::class);
    }

    public function relatedClients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'client_relationships', 'client_id', 'related_client_id')
                    ->withPivot('relationship_type', 'description', 'is_emergency_contact')
                    ->withTimestamps();
    }

    public function reverseRelatedClients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'client_relationships', 'related_client_id', 'client_id')
                    ->withPivot('relationship_type', 'description', 'is_emergency_contact')
                    ->withTimestamps();
    }
}
