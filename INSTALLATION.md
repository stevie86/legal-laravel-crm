# Beratungs-CRM Installation

## Systemanforderungen

- PHP 8.2 oder höher
- Composer
- SQLite (oder MySQL/PostgreSQL)
- Node.js & NPM (optional, für Frontend-Assets)

## Installation

### 1. Projekt herunterladen

```bash
# Option A: Git Clone (falls Repository verfügbar)
git clone <repository-url> beratungs-crm
cd beratungs-crm

# Option B: ZIP-Datei entpacken
unzip beratungs-crm.zip
cd beratungs-crm
```

### 2. Dependencies installieren

```bash
# PHP Dependencies
composer install

# Frontend Dependencies (optional)
npm install
```

### 3. Umgebung konfigurieren

```bash
# .env Datei erstellen
cp .env.example .env

# Application Key generieren
php artisan key:generate
```

### 4. Datenbank einrichten

```bash
# SQLite Datenbank erstellen (Standard)
touch database/database.sqlite

# Migrationen ausführen
php artisan migrate

# Testdaten laden (optional)
php artisan db:seed
```

### 5. Storage verlinken

```bash
php artisan storage:link
```

### 6. Server starten

```bash
# Entwicklungsserver
php artisan serve

# Oder mit spezifischem Host/Port
php artisan serve --host=0.0.0.0 --port=8000
```

## Konfiguration

### Datenbank

Die Anwendung ist standardmäßig für SQLite konfiguriert. Für andere Datenbanken bearbeiten Sie die `.env` Datei:

```env
# MySQL Beispiel
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=beratungs_crm
DB_USERNAME=root
DB_PASSWORD=
```

### E-Mail (optional)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

## Funktionen

### ✅ Implementiert

- **Dashboard** mit Statistiken und Übersichten
- **Klientenverwaltung** (CRUD, Suche, Filter)
- **Beratungssitzungen** (Planung, Verwaltung, Status)
- **Kalenderansicht** mit Monatsübersicht
- **Responsive Design** mit Bootstrap
- **Datenbank-Seeding** mit Testdaten

### ✅ Implementiert

- **Benutzerauthentifizierung** mit Rollen (Admin, Berater)
- **Dashboard** mit Statistiken und Übersichten
- **Klientenverwaltung** (CRUD, Suche, Filter)
- **Beratungssitzungen** (Planung, Verwaltung, Status)
- **Kalenderansicht** mit Monatsübersicht
- **Responsive Design** mit Bootstrap
- **Datenbank-Seeding** mit Testdaten

### 🚧 In Entwicklung

- Dokumentenverwaltung mit Datei-Upload
- Sitzungsnotizen-System
- E-Mail-Benachrichtigungen
- Berichte und Statistiken
- API-Endpunkte

## Verwendung

### Authentifizierung & Rollen

Das System verwendet eine rollenbasierte Authentifizierung. Alle Routen erfordern eine Anmeldung.

- **Administrator (`admin`)**: Hat vollen Zugriff auf alle Funktionen, einschließlich Benutzerverwaltung und Systemeinstellungen.
- **Berater (`counselor`)**: Kann Klienten, Sitzungen und Dokumente verwalten.
- **Standard-Benutzer**: Hat Zugriff auf das Dashboard und das eigene Profil.

### Standard-Benutzer

Nach der Migration und dem Seeding der Datenbank können Sie sich mit folgenden Test-Benutzern anmelden:

- **Admin**: `admin@example.com` / `password`
- **Berater**: `counselor@example.com` / `password`

### Navigation

### Navigation

- **Dashboard**: Übersicht und Statistiken
- **Klienten**: Klientenverwaltung und -details
- **Sitzungen**: Terminplanung und -verwaltung
- **Kalender**: Monatsansicht aller Termine
- **Dokumente**: Dateiverwaltung (in Entwicklung)

### Testdaten

Das System enthält Beispieldaten:
- 3 Testklienten
- Mehrere Beratungssitzungen
- Kalendereinträge

## Support

Bei Fragen oder Problemen:
1. Prüfen Sie die Laravel-Logs: `storage/logs/laravel.log`
2. Stellen Sie sicher, dass alle Dependencies installiert sind
3. Überprüfen Sie die Datenbankverbindung
4. Kontaktieren Sie den Entwickler

## Lizenz

Dieses Projekt ist für den internen Gebrauch entwickelt.
