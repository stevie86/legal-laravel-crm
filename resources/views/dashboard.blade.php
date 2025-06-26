@extends('layouts.app')

@section('title', 'Dashboard - Beratungs-CRM')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistik-Karten -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-number">{{ $stats['total_clients'] }}</div>
                        <div class="text-white-50">Aktive Klienten</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-number">{{ $stats['sessions_today'] }}</div>
                        <div class="text-white-50">Termine heute</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-number">{{ $stats['sessions_this_week'] }}</div>
                        <div class="text-white-50">Diese Woche</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-week fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-number">{{ $stats['sessions_this_month'] }}</div>
                        <div class="text-white-50">Dieser Monat</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Heutige Termine -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-day text-primary me-2"></i>
                    Heutige Termine
                </h5>
                <a href="{{ route('sessions.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Neuer Termin
                </a>
            </div>
            <div class="card-body">
                @if($todaysSessions->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($todaysSessions as $session)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $session->title }}</h6>
                                    <p class="mb-1">
                                        <i class="fas fa-user text-muted me-1"></i>
                                        {{ $session->client->full_name }}
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $session->scheduled_at->format('H:i') }} - 
                                        {{ $session->end_time->format('H:i') }}
                                        @if($session->location)
                                            | <i class="fas fa-map-marker-alt me-1"></i>{{ $session->location }}
                                        @endif
                                    </small>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $session->status === 'scheduled' ? 'primary' : ($session->status === 'completed' ? 'success' : 'warning') }}">
                                        {{ ucfirst($session->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Keine Termine für heute geplant.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Neueste Klienten -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus text-success me-2"></i>
                    Neueste Klienten
                </h5>
                <a href="{{ route('clients.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Neuer Klient
                </a>
            </div>
            <div class="card-body">
                @if($recentClients->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentClients as $client)
                            <a href="{{ route('clients.show', $client) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $client->full_name }}</h6>
                                    <small>{{ $client->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">
                                    <small class="text-muted">{{ $client->client_number }}</small>
                                </p>
                                @if($client->email)
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>{{ $client->email }}
                                    </small>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Noch keine Klienten angelegt.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Kommende Termine -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-plus text-info me-2"></i>
                    Kommende Termine (nächste 7 Tage)
                </h5>
            </div>
            <div class="card-body">
                @if($upcomingSessions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Datum & Zeit</th>
                                    <th>Klient</th>
                                    <th>Titel</th>
                                    <th>Typ</th>
                                    <th>Status</th>
                                    <th>Aktionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingSessions as $session)
                                    <tr>
                                        <td>
                                            <strong>{{ $session->scheduled_at->format('d.m.Y') }}</strong><br>
                                            <small class="text-muted">{{ $session->scheduled_at->format('H:i') }} - {{ $session->end_time->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('clients.show', $session->client) }}" class="text-decoration-none">
                                                {{ $session->client->full_name }}
                                            </a>
                                        </td>
                                        <td>{{ $session->title }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($session->session_type) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $session->status === 'scheduled' ? 'primary' : ($session->status === 'completed' ? 'success' : 'warning') }}">
                                                {{ ucfirst($session->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('sessions.show', $session) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('sessions.edit', $session) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Keine kommenden Termine in den nächsten 7 Tagen.</p>
                        <a href="{{ route('sessions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ersten Termin erstellen
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection