@extends('layouts.app')

@section('title', $client->full_name . ' - Beratungs-CRM')
@section('page-title', 'Klient: ' . $client->full_name)

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Zurück zur Liste
        </a>
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Bearbeiten
        </a>
        <a href="{{ route('sessions.create', ['client_id' => $client->id]) }}" class="btn btn-success">
            <i class="fas fa-calendar-plus"></i> Neuer Termin
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Klienteninformationen -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user text-primary me-2"></i>
                    Klienteninformationen
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr($client->first_name, 0, 1) . substr($client->last_name, 0, 1)) }}
                    </div>
                    <h4 class="mt-2 mb-1">{{ $client->full_name }}</h4>
                    <p class="text-muted">{{ $client->client_number }}</p>
                    <span class="badge bg-{{ $client->status === 'active' ? 'success' : ($client->status === 'inactive' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($client->status) }}
                    </span>
                </div>

                <hr>

                <div class="row g-3">
                    @if($client->age)
                        <div class="col-6">
                            <small class="text-muted">Alter</small>
                            <div>{{ $client->age }} Jahre</div>
                        </div>
                    @endif
                    
                    @if($client->gender)
                        <div class="col-6">
                            <small class="text-muted">Geschlecht</small>
                            <div>
                                {{ $client->gender === 'male' ? 'Männlich' : ($client->gender === 'female' ? 'Weiblich' : ($client->gender === 'diverse' ? 'Divers' : 'Keine Angabe')) }}
                            </div>
                        </div>
                    @endif

                    @if($client->email)
                        <div class="col-12">
                            <small class="text-muted">E-Mail</small>
                            <div>
                                <a href="mailto:{{ $client->email }}">
                                    <i class="fas fa-envelope me-1"></i>{{ $client->email }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($client->mobile)
                        <div class="col-12">
                            <small class="text-muted">Mobiltelefon</small>
                            <div>
                                <a href="tel:{{ $client->mobile }}">
                                    <i class="fas fa-mobile-alt me-1"></i>{{ $client->mobile }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($client->phone)
                        <div class="col-12">
                            <small class="text-muted">Festnetz</small>
                            <div>
                                <a href="tel:{{ $client->phone }}">
                                    <i class="fas fa-phone me-1"></i>{{ $client->phone }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($client->address || $client->city)
                        <div class="col-12">
                            <small class="text-muted">Adresse</small>
                            <div>
                                @if($client->address)
                                    {{ $client->address }}<br>
                                @endif
                                @if($client->postal_code || $client->city)
                                    {{ $client->postal_code }} {{ $client->city }}
                                @endif
                                @if($client->country && $client->country !== 'Deutschland')
                                    <br>{{ $client->country }}
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($client->emergency_contact_name || $client->emergency_contact_phone)
                        <div class="col-12">
                            <small class="text-muted">Notfallkontakt</small>
                            <div>
                                @if($client->emergency_contact_name)
                                    {{ $client->emergency_contact_name }}
                                @endif
                                @if($client->emergency_contact_phone)
                                    <br><a href="tel:{{ $client->emergency_contact_phone }}">{{ $client->emergency_contact_phone }}</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="col-12">
                        <small class="text-muted">Erstellt am</small>
                        <div>{{ $client->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if($client->medical_notes || $client->general_notes)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sticky-note text-warning me-2"></i>
                        Notizen
                    </h5>
                </div>
                <div class="card-body">
                    @if($client->medical_notes)
                        <div class="mb-3">
                            <h6 class="text-danger">Medizinische Notizen</h6>
                            <p class="mb-0">{{ $client->medical_notes }}</p>
                        </div>
                    @endif
                    
                    @if($client->general_notes)
                        <div>
                            <h6 class="text-info">Allgemeine Notizen</h6>
                            <p class="mb-0">{{ $client->general_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Beratungssitzungen und weitere Informationen -->
    <div class="col-lg-8">
        <!-- Beratungssitzungen -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check text-success me-2"></i>
                    Beratungssitzungen
                </h5>
                <a href="{{ route('sessions.create', ['client_id' => $client->id]) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Neue Sitzung
                </a>
            </div>
            <div class="card-body">
                @if($client->counselingSessions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Datum</th>
                                    <th>Titel</th>
                                    <th>Typ</th>
                                    <th>Status</th>
                                    <th>Aktionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($client->counselingSessions as $session)
                                    <tr>
                                        <td>
                                            <strong>{{ $session->scheduled_at->format('d.m.Y') }}</strong><br>
                                            <small class="text-muted">{{ $session->scheduled_at->format('H:i') }}</small>
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
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('sessions.show', $session) }}" class="btn btn-outline-primary" title="Anzeigen">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('sessions.edit', $session) }}" class="btn btn-outline-secondary" title="Bearbeiten">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Noch keine Beratungssitzungen geplant.</p>
                        <a href="{{ route('sessions.create', ['client_id' => $client->id]) }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Erste Sitzung planen
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Dokumente -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt text-info me-2"></i>
                    Dokumente
                </h5>
                <a href="{{ route('documents.create', ['client_id' => $client->id]) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-plus"></i> Dokument hinzufügen
                </a>
            </div>
            <div class="card-body">
                @if($client->documents->count() > 0)
                    <div class="row">
                        @foreach($client->documents as $document)
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-1">{{ $document->title }}</h6>
                                        <p class="card-text text-muted small mb-2">{{ $document->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">{{ $document->file_size_human }}</small>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ $document->download_url }}" class="btn btn-outline-primary" title="Herunterladen">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="{{ route('documents.show', $document) }}" class="btn btn-outline-secondary" title="Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Noch keine Dokumente hochgeladen.</p>
                        <a href="{{ route('documents.create', ['client_id' => $client->id]) }}" class="btn btn-info">
                            <i class="fas fa-plus"></i> Erstes Dokument hinzufügen
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kalendereinträge -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar text-primary me-2"></i>
                    Kommende Termine
                </h5>
            </div>
            <div class="card-body">
                @if($client->calendarEvents->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($client->calendarEvents as $event)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $event->title }}</h6>
                                    <p class="mb-1 text-muted">{{ $event->description }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $event->formatted_date_time }}
                                        @if($event->location)
                                            | <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location }}
                                        @endif
                                    </small>
                                </div>
                                <span class="badge" style="background-color: {{ $event->color }}">
                                    {{ ucfirst($event->event_type) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Keine kommenden Termine.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection