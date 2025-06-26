@extends('layouts.app')

@section('title', 'Neuer Klient - Beratungs-CRM')
@section('page-title', 'Neuer Klient')

@section('page-actions')
    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Zurück zur Liste
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus text-success me-2"></i>
                    Klientendaten erfassen
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('clients.store') }}">
                    @csrf
                    
                    <!-- Persönliche Daten -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-2"></i>Persönliche Daten
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">Vorname <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Nachname <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="birth_date" class="form-label">Geburtsdatum</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                   id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Geschlecht</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Bitte wählen</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Männlich</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Weiblich</option>
                                <option value="diverse" {{ old('gender') === 'diverse' ? 'selected' : '' }}>Divers</option>
                                <option value="not_specified" {{ old('gender') === 'not_specified' ? 'selected' : '' }}>Keine Angabe</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kontaktdaten -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-address-book me-2"></i>Kontaktdaten
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">E-Mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="mobile" class="form-label">Mobiltelefon</label>
                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror" 
                                   id="mobile" name="mobile" value="{{ old('mobile') }}">
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Festnetz</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Adresse
                            </h6>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Straße und Hausnummer</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="postal_code" class="form-label">Postleitzahl</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                   id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">Stadt</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                   id="city" name="city" value="{{ old('city') }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="country" class="form-label">Land</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                   id="country" name="country" value="{{ old('country', 'Deutschland') }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Notfallkontakt -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-phone-alt me-2"></i>Notfallkontakt
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="emergency_contact_name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                   id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}">
                            @error('emergency_contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="emergency_contact_phone" class="form-label">Telefonnummer</label>
                            <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                   id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}">
                            @error('emergency_contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Notizen -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-sticky-note me-2"></i>Notizen
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="medical_notes" class="form-label">Medizinische Notizen</label>
                            <textarea class="form-control @error('medical_notes') is-invalid @enderror" 
                                      id="medical_notes" name="medical_notes" rows="4" 
                                      placeholder="Allergien, Medikamente, Vorerkrankungen...">{{ old('medical_notes') }}</textarea>
                            @error('medical_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="general_notes" class="form-label">Allgemeine Notizen</label>
                            <textarea class="form-control @error('general_notes') is-invalid @enderror" 
                                      id="general_notes" name="general_notes" rows="4" 
                                      placeholder="Besonderheiten, Präferenzen...">{{ old('general_notes') }}</textarea>
                            @error('general_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-toggle-on me-2"></i>Status
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Klientenstatus <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktiv</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                                <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archiviert</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Abbrechen
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Klient erstellen
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