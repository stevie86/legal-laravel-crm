<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    // Berechtigungsmethoden
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCounselor(): bool
    {
        return $this->role === 'counselor';
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    public function canManageClients(): bool
    {
        return $this->isAdmin() || $this->isCounselor();
    }

    public function canManageSessions(): bool
    {
        return $this->isAdmin() || $this->isCounselor();
    }

    public function canViewReports(): bool
    {
        return $this->isAdmin() || $this->isCounselor();
    }

    public function getRoleDisplayAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'counselor' => 'Berater',
            'viewer' => 'Betrachter',
            default => 'Unbekannt'
        };
    }

    // Beziehungen
    public function counselingSessions()
    {
        return $this->hasMany(CounselingSession::class);
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }
}
