@extends('layouts.crm')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<!-- Willkommensnachricht -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary">
            <h4 class="alert-heading">
                <i class="bi bi-person-circle me-2"></i>Willkommen, {{ auth()->user()->name }}!
            </h4>
            <p class="mb-0">
                Sie sind als <strong>{{ auth()->user()->role_display }}</strong> angemeldet. 
                Hier ist eine Übersicht über Ihre wichtigsten Daten.
            </p>
        </div>
    </div>
</div>

<!-- Statistiken -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_clients'] }}</h4>
                        <p class="card-text">Klienten gesamt</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_sessions'] }}</h4>
                        <p class="card-text">Sitzungen gesamt</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-event fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['sessions_this_month'] }}</h4>
                        <p class="card-text">Sitzungen diesen Monat</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['upcoming_sessions'] }}</h4>
                        <p class="card-text">Kommende Sitzungen</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Neueste Klienten -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-plus me-2"></i>Neueste Klienten
                </h5>
                @if(auth()->user()->canManageClients())
                    <a href="{{ route('clients.index') }}" class="btn btn-sm btn-outline-primary">
                        Alle anzeigen
                    </a>
                @endif
            </div>
            <div class="card-body">
                @forelse($stats['recent_clients'] as $client)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $client->first_name }} {{ $client->last_name }}</strong><br>
                            <small class="text-muted">{{ $client->email }}</small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">{{ $client->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Keine Klienten gefunden.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Kommende Sitzungen -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-calendar-event me-2"></i>Kommende Sitzungen
                </h5>
                @if(auth()->user()->canManageSessions())
                    <a href="{{ route('sessions.index') }}" class="btn btn-sm btn-outline-primary">
                        Alle anzeigen
                    </a>
                @endif
            </div>
            <div class="card-body">
                @forelse($stats['upcoming_sessions_list'] as $session)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $session->title }}</strong><br>
                            <small class="text-muted">
                                mit {{ $session->client->first_name }} {{ $session->client->last_name }}
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $session->status === 'scheduled' ? 'primary' : 'secondary' }}">
                                {{ $session->scheduled_at->format('d.m. H:i') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Keine kommenden Sitzungen.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Schnellaktionen -->
@if(auth()->user()->canManageClients())
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
                            <a href="{{ route('clients.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-person-plus me-2"></i>Neuer Klient
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('sessions.create') }}" class="btn btn-success w-100">
                                <i class="bi bi-calendar-plus me-2"></i>Neue Sitzung
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('calendar.index') }}" class="btn btn-info w-100">
                                <i class="bi bi-calendar3 me-2"></i>Kalender
                            </a>
                        </div>
                        @if(auth()->user()->isAdmin())
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-warning w-100">
                                    <i class="bi bi-gear me-2"></i>Administration
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
