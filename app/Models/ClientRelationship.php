<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientRelationship extends Model
{
    protected $fillable = [
        'client_id',
        'related_client_id',
        'relationship_type',
        'description',
        'is_emergency_contact',
    ];

    protected $casts = [
        'is_emergency_contact' => 'boolean',
    ];

    public function getRelationshipTypeDisplayAttribute(): string
    {
        return match ($this->relationship_type) {
            'spouse' => 'Ehepartner/in',
            'partner' => 'Partner/in',
            'child' => 'Kind',
            'parent' => 'Elternteil',
            'sibling' => 'Geschwister',
            'grandparent' => 'Großelternteil',
            'grandchild' => 'Enkelkind',
            'friend' => 'Freund/in',
            'colleague' => 'Kollege/in',
            'other' => 'Andere',
            default => $this->relationship_type
        };
    }

    // Beziehungen
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function relatedClient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'related_client_id');
    }
}
