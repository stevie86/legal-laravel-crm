@extends('layouts.app')

@section('title', 'Neue Beratungssitzung - Beratungs-CRM')
@section('page-title', 'Neue Beratungssitzung')

@section('page-actions')
    <a href="{{ route('sessions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Zurück zur Liste
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-plus text-success me-2"></i>
                    Beratungssitzung planen
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('sessions.store') }}">
                    @csrf
                    
                    <!-- Grunddaten -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>Grunddaten
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="client_id" class="form-label">Klient <span class="text-danger">*</span></label>
                            <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                                <option value="">Klient auswählen</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" 
                                            {{ (old('client_id') == $client->id || ($selectedClient && $selectedClient->id == $client->id)) ? 'selected' : '' }}>
                                        {{ $client->full_name }} ({{ $client->client_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Titel <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Beschreibung</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Termindetails -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-clock me-2"></i>Termindetails
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="scheduled_at" class="form-label">Datum und Uhrzeit <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                   id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}" required>
                            @error('scheduled_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="duration_minutes" class="form-label">Dauer (Minuten) <span class="text-danger">*</span></label>
                            <select class="form-select @error('duration_minutes') is-invalid @enderror" id="duration_minutes" name="duration_minutes" required>
                                <option value="">Dauer wählen</option>
                                <option value="30" {{ old('duration_minutes') == '30' ? 'selected' : '' }}>30 Minuten</option>
                                <option value="45" {{ old('duration_minutes') == '45' ? 'selected' : '' }}>45 Minuten</option>
                                <option value="50" {{ old('duration_minutes', '50') == '50' ? 'selected' : '' }}>50 Minuten</option>
                                <option value="60" {{ old('duration_minutes') == '60' ? 'selected' : '' }}>60 Minuten</option>
                                <option value="90" {{ old('duration_minutes') == '90' ? 'selected' : '' }}>90 Minuten</option>
                                <option value="120" {{ old('duration_minutes') == '120' ? 'selected' : '' }}>120 Minuten</option>
                            </select>
                            @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="session_type" class="form-label">Sitzungstyp <span class="text-danger">*</span></label>
                            <select class="form-select @error('session_type') is-invalid @enderror" id="session_type" name="session_type" required>
                                <option value="">Typ auswählen</option>
                                <option value="individual" {{ old('session_type', 'individual') == 'individual' ? 'selected' : '' }}>Einzelberatung</option>
                                <option value="couple" {{ old('session_type') == 'couple' ? 'selected' : '' }}>Paarberatung</option>
                                <option value="family" {{ old('session_type') == 'family' ? 'selected' : '' }}>Familienberatung</option>
                                <option value="group" {{ old('session_type') == 'group' ? 'selected' : '' }}>Gruppenberatung</option>
                                <option value="assessment" {{ old('session_type') == 'assessment' ? 'selected' : '' }}>Assessment</option>
                            </select>
                            @error('session_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Ort</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location', 'Praxis Raum 1') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Weitere Details -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-cog me-2"></i>Weitere Details
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fee" class="form-label">Honorar (€)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('fee') is-invalid @enderror" 
                                   id="fee" name="fee" value="{{ old('fee') }}">
                            @error('fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="scheduled" {{ old('status', 'scheduled') == 'scheduled' ? 'selected' : '' }}>Geplant</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Abgeschlossen</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Abgesagt</option>
                                <option value="no_show" {{ old('status') == 'no_show' ? 'selected' : '' }}>Nicht erschienen</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="notes" class="form-label">Notizen</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="4" 
                                      placeholder="Vorbereitungsnotizen, besondere Hinweise...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('sessions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Abbrechen
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Sitzung erstellen
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Automatisches Setzen der Standard-Endzeit basierend auf Dauer
document.getElementById('duration_minutes').addEventListener('change', function() {
    const scheduledAt = document.getElementById('scheduled_at');
    if (scheduledAt.value && this.value) {
        const startTime = new Date(scheduledAt.value);
        const endTime = new Date(startTime.getTime() + (parseInt(this.value) * 60000));
        // Hier könnte man ein End-Zeit Feld aktualisieren, falls vorhanden
    }
});
</script>
@endpush