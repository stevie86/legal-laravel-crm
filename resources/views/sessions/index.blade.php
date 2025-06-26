@extends('layouts.app')

@section('title', 'Beratungssitzungen - Beratungs-CRM')
@section('page-title', 'Beratungssitzungen')

@section('page-actions')
    <a href="{{ route('sessions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Neue Sitzung
    </a>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('sessions.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="client_id" class="form-label">Klient</label>
                        <select class="form-select" id="client_id" name="client_id">
                            <option value="">Alle Klienten</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Alle Status</option>
                            <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Geplant</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Abgeschlossen</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Abgesagt</option>
                            <option value="no_show" {{ request('status') === 'no_show' ? 'selected' : '' }}>Nicht erschienen</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Von Datum</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Bis Datum</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Filtern
                        </button>
                        <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Zurücksetzen
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check text-success me-2"></i>
                    Sitzungsliste ({{ $sessions->total() }} Sitzungen)
                </h5>
            </div>
            <div class="card-body">
                @if($sessions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Datum & Zeit</th>
                                    <th>Klient</th>
                                    <th>Titel</th>
                                    <th>Typ</th>
                                    <th>Dauer</th>
                                    <th>Status</th>
                                    <th>Honorar</th>
                                    <th>Aktionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $session)
                                    <tr>
                                        <td>
                                            <strong>{{ $session->scheduled_at->format('d.m.Y') }}</strong><br>
                                            <small class="text-muted">{{ $session->scheduled_at->format('H:i') }} - {{ $session->end_time->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('clients.show', $session->client) }}" class="text-decoration-none">
                                                <strong>{{ $session->client->full_name }}</strong>
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $session->client->client_number }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $session->title }}</strong>
                                            @if($session->description)
                                                <br>
                                                <small class="text-muted">{{ Str::limit($session->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $session->session_type === 'individual' ? 'Einzelberatung' : ($session->session_type === 'couple' ? 'Paarberatung' : ($session->session_type === 'family' ? 'Familienberatung' : ($session->session_type === 'group' ? 'Gruppenberatung' : 'Assessment'))) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $session->duration_minutes }} Min.
                                            @if($session->location)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt"></i> {{ $session->location }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $session->status === 'scheduled' ? 'primary' : ($session->status === 'completed' ? 'success' : ($session->status === 'cancelled' ? 'warning' : 'danger')) }}">
                                                {{ $session->status === 'scheduled' ? 'Geplant' : ($session->status === 'completed' ? 'Abgeschlossen' : ($session->status === 'cancelled' ? 'Abgesagt' : 'Nicht erschienen')) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($session->fee)
                                                <strong>{{ number_format($session->fee, 2, ',', '.') }} €</strong>
                                                @if($session->fee_paid)
                                                    <br><span class="badge bg-success">Bezahlt</span>
                                                @else
                                                    <br><span class="badge bg-warning">Offen</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('sessions.show', $session) }}" class="btn btn-sm btn-outline-primary" title="Anzeigen">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('sessions.edit', $session) }}" class="btn btn-sm btn-outline-secondary" title="Bearbeiten">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Als abgeschlossen markieren">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmDelete('{{ $session->id }}', '{{ $session->title }}')" 
                                                        title="Löschen">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $sessions->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Keine Beratungssitzungen gefunden</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['client_id', 'status', 'date_from', 'date_to']))
                                Keine Sitzungen entsprechen den Filterkriterien.
                            @else
                                Noch keine Beratungssitzungen geplant.
                            @endif
                        </p>
                        <a href="{{ route('sessions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Erste Sitzung planen
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sitzung löschen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Sind Sie sicher, dass Sie die Sitzung <strong id="sessionTitle"></strong> löschen möchten?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Diese Aktion kann nicht rückgängig gemacht werden. Alle zugehörigen Daten werden ebenfalls gelöscht.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Löschen</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(sessionId, sessionTitle) {
    document.getElementById('sessionTitle').textContent = sessionTitle;
    document.getElementById('deleteForm').action = '/sessions/' + sessionId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush