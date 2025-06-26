@extends('layouts.crm')

@section('title', 'Admin Dashboard')
@section('header', 'Administration Dashboard')

@section('content')
<div class="row">
    <!-- Statistiken -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_users'] }}</h4>
                        <p class="card-text">Benutzer gesamt</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['active_users'] }}</h4>
                        <p class="card-text">Aktive Benutzer</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-person-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_clients'] }}</h4>
                        <p class="card-text">Klienten</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-person-lines-fill fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['sessions_this_month'] }}</h4>
                        <p class="card-text">Sitzungen diesen Monat</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-event fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Benutzerrollen -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Benutzerrollen
                </h5>
            </div>
            <div class="card-body">
                @foreach($stats['user_roles'] as $role)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>
                            @switch($role->role)
                                @case('admin')
                                    <i class="bi bi-shield-check text-danger me-2"></i>Administrator
                                    @break
                                @case('counselor')
                                    <i class="bi bi-person-badge text-primary me-2"></i>Berater
                                    @break
                                @case('viewer')
                                    <i class="bi bi-eye text-secondary me-2"></i>Betrachter
                                    @break
                            @endswitch
                        </span>
                        <span class="badge bg-secondary">{{ $role->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Neueste Benutzer -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-plus me-2"></i>Neueste Benutzer
                </h5>
            </div>
            <div class="card-body">
                @forelse($stats['recent_users'] as $user)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $user->name }}</strong><br>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'counselor' ? 'primary' : 'secondary') }}">
                                {{ $user->role_display }}
                            </span><br>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Keine Benutzer gefunden.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Schnellaktionen -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>Schnellaktionen
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-person-plus me-2"></i>Neuer Benutzer
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-people me-2"></i>Benutzerverwaltung
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.reports') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-graph-up me-2"></i>Berichte
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.settings') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-sliders me-2"></i>Einstellungen
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection