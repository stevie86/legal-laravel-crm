@extends('layouts.app')

@section('title', 'Klienten - Beratungs-CRM')
@section('page-title', 'Klienten')

@section('page-actions')
    <a href="{{ route('clients.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Neuer Klient
    </a>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('clients.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Suche</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Name, E-Mail oder Klientennummer...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Alle Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktiv</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archiviert</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Suchen
                        </button>
                        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
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
                    <i class="fas fa-users text-primary me-2"></i>
                    Klientenliste ({{ $clients->total() }} Klienten)
                </h5>
            </div>
            <div class="card-body">
                @if($clients->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Klientennummer</th>
                                    <th>Name</th>
                                    <th>Kontakt</th>
                                    <th>Alter</th>
                                    <th>Status</th>
                                    <th>Erstellt</th>
                                    <th>Aktionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $client->client_number }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $client->full_name }}</strong>
                                                @if($client->gender)
                                                    <small class="text-muted">
                                                        ({{ $client->gender === 'male' ? 'm' : ($client->gender === 'female' ? 'w' : 'd') }})
                                                    </small>
                                                @endif
                                            </div>
                                            @if($client->city)
                                                <small class="text-muted">{{ $client->city }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($client->email)
                                                <div>
                                                    <i class="fas fa-envelope text-muted me-1"></i>
                                                    <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                                                </div>
                                            @endif
                                            @if($client->mobile)
                                                <div>
                                                    <i class="fas fa-mobile-alt text-muted me-1"></i>
                                                    <a href="tel:{{ $client->mobile }}">{{ $client->mobile }}</a>
                                                </div>
                                            @elseif($client->phone)
                                                <div>
                                                    <i class="fas fa-phone text-muted me-1"></i>
                                                    <a href="tel:{{ $client->phone }}">{{ $client->phone }}</a>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($client->age)
                                                {{ $client->age }} Jahre
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $client->status === 'active' ? 'success' : ($client->status === 'inactive' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($client->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $client->created_at->format('d.m.Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-primary" title="Anzeigen">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary" title="Bearbeiten">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmDelete('{{ $client->id }}', '{{ $client->full_name }}')" 
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
                        {{ $clients->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Keine Klienten gefunden</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'status']))
                                Keine Klienten entsprechen den Suchkriterien.
                            @else
                                Noch keine Klienten angelegt.
                            @endif
                        </p>
                        <a href="{{ route('clients.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ersten Klient erstellen
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
                <h5 class="modal-title">Klient löschen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Sind Sie sicher, dass Sie den Klient <strong id="clientName"></strong> löschen möchten?</p>
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
function confirmDelete(clientId, clientName) {
    document.getElementById('clientName').textContent = clientName;
    document.getElementById('deleteForm').action = '/clients/' + clientId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush