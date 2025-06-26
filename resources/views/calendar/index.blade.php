@extends('layouts.crm')

@section('title', 'Kalender')
@section('header', 'Kalender - ' . $date->locale('de')->isoFormat('MMMM YYYY'))

@section('header-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('calendar.index', ['month' => $date->copy()->subMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary">
            <i class="bi bi-chevron-left"></i> Vorheriger Monat
        </a>
        <a href="{{ route('calendar.index', ['month' => Carbon\Carbon::now()->format('Y-m')]) }}" class="btn btn-outline-primary">
            Heute
        </a>
        <a href="{{ route('calendar.index', ['month' => $date->copy()->addMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary">
            Nächster Monat <i class="bi bi-chevron-right"></i>
        </a>
        @if(auth()->user()->canManageSessions())
            <a href="{{ route('sessions.create') }}" class="btn btn-primary ms-2">
                <i class="bi bi-plus"></i> Neuer Termin
            </a>
        @endif
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-9 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar text-primary me-2"></i>
                    {{ $date->locale('de')->isoFormat('MMMM YYYY') }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" style="table-layout: fixed;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center py-3" style="width: 14.28%;">Montag</th>
                                <th class="text-center py-3" style="width: 14.28%;">Dienstag</th>
                                <th class="text-center py-3" style="width: 14.28%;">Mittwoch</th>
                                <th class="text-center py-3" style="width: 14.28%;">Donnerstag</th>
                                <th class="text-center py-3" style="width: 14.28%;">Freitag</th>
                                <th class="text-center py-3" style="width: 14.28%;">Samstag</th>
                                <th class="text-center py-3" style="width: 14.28%;">Sonntag</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calendar as $week)
                                <tr>
                                    @foreach($week as $day)
                                        <td class="p-2 align-top position-relative" style="height: 120px; {{ !$day['isCurrentMonth'] ? 'background-color: #f8f9fa;' : '' }}">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <span class="fw-bold {{ $day['isToday'] ? 'text-primary' : ($day['isCurrentMonth'] ? 'text-dark' : 'text-muted') }}">
                                                    {{ $day['date']->format('j') }}
                                                </span>
                                                @if($day['isToday'])
                                                    <span class="badge bg-primary">Heute</span>
                                                @endif
                                            </div>
                                            
                                            @if($day['events']->count() > 0)
                                                <div class="events-container" style="max-height: 80px; overflow-y: auto;">
                                                    @foreach($day['events']->take(3) as $event)
                                                        <div class="event-item mb-1">
                                                            @if($event->counseling_session_id)
                                                                <a href="{{ route('sessions.show', $event->counseling_session_id) }}" 
                                                                   class="text-decoration-none">
                                                                    <div class="small p-1 rounded text-white" 
                                                                         style="background-color: {{ $event->color }}; font-size: 0.7rem;">
                                                                        <div class="fw-bold">{{ $event->start_time->format('H:i') }}</div>
                                                                        <div>{{ Str::limit($event->title, 20) }}</div>
                                                                    </div>
                                                                </a>
                                                            @else
                                                                <div class="small p-1 rounded text-white" 
                                                                     style="background-color: {{ $event->color }}; font-size: 0.7rem;">
                                                                    <div class="fw-bold">{{ $event->start_time->format('H:i') }}</div>
                                                                    <div>{{ Str::limit($event->title, 20) }}</div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                    
                                                    @if($day['events']->count() > 3)
                                                        <div class="small text-muted">
                                                            +{{ $day['events']->count() - 3 }} weitere
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar mit Terminen -->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list text-info me-2"></i>
                    Termine diesen Monat
                </h5>
            </div>
            <div class="card-body">
                @if($events->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($events->take(10) as $event)
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        @if($event->counseling_session_id)
                                            <a href="{{ route('sessions.show', $event->counseling_session_id) }}" 
                                               class="text-decoration-none">
                                                <h6 class="mb-1">{{ $event->title }}</h6>
                                            </a>
                                        @else
                                            <h6 class="mb-1">{{ $event->title }}</h6>
                                        @endif
                                        
                                        <p class="mb-1 text-muted small">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $event->start_time->format('d.m.Y') }}
                                        </p>
                                        <p class="mb-1 text-muted small">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}
                                        </p>
                                        
                                        @if($event->client)
                                            <p class="mb-1 text-muted small">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $event->client->full_name }}
                                            </p>
                                        @endif
                                        
                                        @if($event->location)
                                            <p class="mb-0 text-muted small">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $event->location }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="badge" style="background-color: {{ $event->color }}">
                                        {{ ucfirst($event->event_type) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($events->count() > 10)
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                ... und {{ $events->count() - 10 }} weitere Termine
                            </small>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Keine Termine in diesem Monat.</p>
                        <a href="{{ route('sessions.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Termin erstellen
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Legende -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle text-secondary me-2"></i>
                    Legende
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="me-2" style="width: 20px; height: 15px; background-color: #3788d8; border-radius: 3px;"></div>
                    <span class="small">Beratungssitzungen</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <div class="me-2" style="width: 20px; height: 15px; background-color: #28a745; border-radius: 3px;"></div>
                    <span class="small">Team-Meetings</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-2" style="width: 20px; height: 15px; background-color: #ffc107; border-radius: 3px;"></div>
                    <span class="small">Fortbildungen</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.event-item {
    cursor: pointer;
}
.event-item:hover {
    opacity: 0.8;
}
.table td {
    vertical-align: top;
}
</style>
@endpush