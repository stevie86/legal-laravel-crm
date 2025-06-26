@extends('layouts.crm')

@section('title', 'System-Einstellungen')
@section('header', 'System-Einstellungen')

@section('content')
<div class="row">
    <!-- Allgemeine Einstellungen -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gear me-2"></i>Allgemeine Einstellungen
                </h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="app_name" class="form-label">Anwendungsname</label>
                        <input type="text" class="form-control" id="app_name" value="Beratungs-CRM">
                    </div>
                    
                    <div class="mb-3">
                        <label for="timezone" class="form-label">Zeitzone</label>
                        <select class="form-select" id="timezone">
                            <option value="Europe/Berlin" selected>Europe/Berlin</option>
                            <option value="Europe/Vienna">Europe/Vienna</option>
                            <option value="Europe/Zurich">Europe/Zurich</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="date_format" class="form-label">Datumsformat</label>
                        <select class="form-select" id="date_format">
                            <option value="d.m.Y" selected>DD.MM.YYYY</option>
                            <option value="Y-m-d">YYYY-MM-DD</option>
                            <option value="m/d/Y">MM/DD/YYYY</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Speichern
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- E-Mail-Einstellungen -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-envelope me-2"></i>E-Mail-Einstellungen
                </h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="mail_driver" class="form-label">E-Mail-Treiber</label>
                        <select class="form-select" id="mail_driver">
                            <option value="smtp" selected>SMTP</option>
                            <option value="sendmail">Sendmail</option>
                            <option value="log">Log (Entwicklung)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mail_host" class="form-label">SMTP-Host</label>
                        <input type="text" class="form-control" id="mail_host" placeholder="smtp.gmail.com">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mail_port" class="form-label">Port</label>
                            <input type="number" class="form-control" id="mail_port" value="587">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mail_encryption" class="form-label">Verschlüsselung</label>
                            <select class="form-select" id="mail_encryption">
                                <option value="tls" selected>TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="">Keine</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Speichern
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sicherheitseinstellungen -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-shield-check me-2"></i>Sicherheitseinstellungen
                </h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="session_lifetime" class="form-label">Session-Lebensdauer (Minuten)</label>
                        <input type="number" class="form-control" id="session_lifetime" value="120">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_min_length" class="form-label">Minimale Passwort-Länge</label>
                        <input type="number" class="form-control" id="password_min_length" value="8">
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="require_email_verification" checked>
                        <label class="form-check-label" for="require_email_verification">
                            E-Mail-Verifizierung erforderlich
                        </label>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="enable_two_factor">
                        <label class="form-check-label" for="enable_two_factor">
                            Zwei-Faktor-Authentifizierung aktivieren
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Speichern
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Backup-Einstellungen -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-archive me-2"></i>Backup-Einstellungen
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Automatische Backups</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="backup_frequency" id="backup_daily" value="daily">
                        <label class="form-check-label" for="backup_daily">Täglich</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="backup_frequency" id="backup_weekly" value="weekly" checked>
                        <label class="form-check-label" for="backup_weekly">Wöchentlich</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="backup_frequency" id="backup_monthly" value="monthly">
                        <label class="form-check-label" for="backup_monthly">Monatlich</label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <button type="button" class="btn btn-outline-primary" onclick="createBackup()">
                        <i class="bi bi-download me-1"></i>Backup jetzt erstellen
                    </button>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Letzte Backups</label>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            backup_2025_06_26.sql
                            <span class="badge bg-success">Heute</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            backup_2025_06_19.sql
                            <span class="badge bg-secondary">7 Tage</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System-Informationen -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>System-Informationen
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Laravel Version:</strong><br>
                        <span class="text-muted">{{ app()->version() }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>PHP Version:</strong><br>
                        <span class="text-muted">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Datenbank:</strong><br>
                        <span class="text-muted">SQLite</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Server:</strong><br>
                        <span class="text-muted">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unbekannt' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function createBackup() {
    if (confirm('Möchten Sie jetzt ein Backup erstellen?')) {
        alert('Backup-Funktion wird in einer zukünftigen Version implementiert.');
    }
}
</script>
@endpush