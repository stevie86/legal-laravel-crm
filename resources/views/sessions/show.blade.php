@extends('layouts.app')

@section('title', $session->title . ' - Beratungs-CRM')
@section('page-title', 'Beratungssitzung: ' . $session->title)

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('sessions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Zurück zur Liste
        </a>
        <a href="{{ route('sessions.edit', $session) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Bearbeiten
        </a>
        <a href="{{ route('clients.show', $session->client) }}" class="btn btn-info">
            <i class="fas fa-user"></i> Klient anzeigen
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Sitzungsinformationen -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check text-success me-2"></i>
                    Sitzungsdetails
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Klient</h6>
                        <p class="mb-0">
                            <a href="{{ route('clients.show', $session->client) }}" class="text-decoration-none">
                                <strong>{{ $session->client->full_name }}</strong>
                            </a>
                            <br>
                            <small class="text-muted">{{ $session->client->client_number }}</small>
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Berater</h6>
                        <p class="mb-0">{{ $session->user->name }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Datum & Zeit</h6>
                        <p class="mb-0">
                            <strong>{{ $session->scheduled_at->format('d.m.Y') }}</strong><br>
                            {{ $session->scheduled_at->format('H:i') }} - {{ $session->end_time->format('H:i') }} Uhr
                            <small class="text-muted">({{ $session->duration_minutes }} Min.)</small>
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Status</h6>
                        <span class="badge bg-{{ $session->status === 'scheduled' ? 'primary' : ($session->status === 'completed' ? 'success' : ($session->status === 'cancelled' ? 'warning' : 'danger')) }} fs-6">
                            {{ ucfirst($session->status) }}
                        </span>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Sitzungstyp</h6>
                        <p class="mb-0">
                            <span class="badge bg-secondary">
                                {{ $session->session_type === 'individual' ? 'Einzelberatung' : ($session->session_type === 'couple' ? 'Paarberatung' : ($session->session_type === 'family' ? 'Familienberatung' : ($session->session_type === 'group' ? 'Gruppenberatung' : 'Assessment'))) }}
                            </span>
                        </p>
                    </div>
                    
                    @if($session->location)
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Ort</h6>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                {{ $session->location }}
                            </p>
                        </div>
                    @endif
                    
                    @if($session->fee)
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Honorar</h6>
                            <p class="mb-0">
                                <strong>{{ number_format($session->fee, 2, ',', '.') }} €</strong>
                                @if($session->fee_paid)
                                    <span class="badge bg-success ms-2">Bezahlt</span>
                                @else
                                    <span class="badge bg-warning ms-2">Offen</span>
                                @endif
                            </p>
                        </div>
                    @endif
                    
                    @if($session->description)
                        <div class="col-12 mb-3">
                            <h6 class="text-muted">Beschreibung</h6>
                            <p class="mb-0">{{ $session->description }}</p>
                        </div>
                    @endif
                    
                    @if($session->notes)
                        <div class="col-12 mb-3">
                            <h6 class="text-muted">Notizen</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $session->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sitzungsnotizen -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-sticky-note text-warning me-2"></i>
                    Sitzungsnotizen
                </h5>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                    <i class="fas fa-plus"></i> Notiz hinzufügen
                </button>
            </div>
            <div class="card-body">
                @if($session->sessionNotes->count() > 0)
                    <div class="timeline">
                        @foreach($session->sessionNotes as $note)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $note->title }}</h6>
                                        <small class="text-muted">{{ $note->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                    <p class="mb-0">{{ $note->content }}</p>
                                    @if($note->is_confidential)
                                        <span class="badge bg-danger mt-2">Vertraulich</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-sticky-note fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Noch keine Sitzungsnotizen vorhanden.</p>
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                            <i class="fas fa-plus"></i> Erste Notiz hinzufügen
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Schnellaktionen -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt text-primary me-2"></i>
                    Schnellaktionen
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($session->status === 'scheduled')
                        <form method="POST" action="{{ route('sessions.update', $session) }}" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <input type="hidden" name="client_id" value="{{ $session->client_id }}">
                            <input type="hidden" name="title" value="{{ $session->title }}">
                            <input type="hidden" name="scheduled_at" value="{{ $session->scheduled_at }}">
                            <input type="hidden" name="duration_minutes" value="{{ $session->duration_minutes }}">
                            <input type="hidden" name="session_type" value="{{ $session->session_type }}">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check"></i> Als abgeschlossen markieren
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('sessions.create', ['client_id' => $session->client_id]) }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Folgetermin planen
                    </a>
                    
                    <a href="{{ route('documents.create', ['client_id' => $session->client_id, 'session_id' => $session->id]) }}" class="btn btn-info">
                        <i class="fas fa-file-upload"></i> Dokument hinzufügen
                    </a>
                </div>
            </div>
        </div>

        <!-- Dokumente -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt text-info me-2"></i>
                    Dokumente
                </h5>
            </div>
            <div class="card-body">
                @if($session->documents->count() > 0)
                    @foreach($session->documents as $document)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $document->title }}</strong><br>
                                <small class="text-muted">{{ $document->file_size_human }}</small>
                            </div>
                            <a href="{{ $document->download_url }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                        @if(!$loop->last)<hr>@endif
                    @endforeach
                @else
                    <p class="text-muted text-center">Keine Dokumente vorhanden.</p>
                @endif
            </div>
        </div>

        <!-- Kalendereinträge -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar text-primary me-2"></i>
                    Kalendereinträge
                </h5>
            </div>
            <div class="card-body">
                @if($session->calendarEvents->count() > 0)
                    @foreach($session->calendarEvents as $event)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $event->title }}</strong><br>
                                <small class="text-muted">{{ $event->formatted_date_time }}</small>
                            </div>
                            <span class="badge" style="background-color: {{ $event->color }}">
                                {{ ucfirst($event->event_type) }}
                            </span>
                        </div>
                        @if(!$loop->last)<hr>@endif
                    @endforeach
                @else
                    <p class="text-muted text-center">Keine Kalendereinträge vorhanden.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal für neue Notiz -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Neue Sitzungsnotiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#" onsubmit="alert('Notiz-Funktion wird in einer späteren Version implementiert.'); return false;">
                @csrf
                <input type="hidden" name="counseling_session_id" value="{{ $session->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="note_title" class="form-label">Titel</label>
                        <input type="text" class="form-control" id="note_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="note_content" class="form-label">Inhalt</label>
                        <textarea class="form-control" id="note_content" name="content" rows="5" required></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="note_confidential" name="is_confidential" value="1">
                        <label class="form-check-label" for="note_confidential">
                            Vertraulich markieren
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-warning">Notiz speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection