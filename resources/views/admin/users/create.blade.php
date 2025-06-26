@extends('layouts.crm')

@section('title', 'Neuer Benutzer')
@section('header', 'Neuer Benutzer')

@section('header-actions')
    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Zurück
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">E-Mail *</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Passwort *</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Passwort bestätigen *</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Rolle *</label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" 
                                    name="role" 
                                    required>
                                <option value="">Rolle auswählen...</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                    Administrator
                                </option>
                                <option value="counselor" {{ old('role') === 'counselor' ? 'selected' : '' }}>
                                    Berater
                                </option>
                                <option value="viewer" {{ old('role') === 'viewer' ? 'selected' : '' }}>
                                    Betrachter
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Benutzer ist aktiv
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle me-2"></i>Rollenberechtigungen:</h6>
                            <ul class="mb-0">
                                <li><strong>Administrator:</strong> Vollzugriff auf alle Funktionen und Benutzerverwaltung</li>
                                <li><strong>Berater:</strong> Kann Klienten und Sitzungen verwalten, keine Benutzerverwaltung</li>
                                <li><strong>Betrachter:</strong> Kann nur Daten einsehen, keine Bearbeitung</li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>Abbrechen
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Benutzer erstellen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection