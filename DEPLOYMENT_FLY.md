# Beratungs-CRM Deployment auf Fly.io

Diese Anleitung führt Sie durch den kompletten Deployment-Prozess des Beratungs-CRM auf Fly.io.

## 📋 Voraussetzungen

### 1. Fly.io Account und CLI
```bash
# Fly CLI installieren (macOS)
brew install flyctl

# Fly CLI installieren (Linux)
curl -L https://fly.io/install.sh | sh

# Fly CLI installieren (Windows)
# Download von https://github.com/superfly/flyctl/releases

# Anmelden bei Fly.io
flyctl auth login
```

### 2. Lokale Entwicklungsumgebung
- PHP 8.2+
- Composer
- Node.js & NPM
- Git

## 🚀 Deployment-Schritte

### Schritt 1: Projekt vorbereiten

#### 1.1 Assets kompilieren
```bash
# In das Projektverzeichnis wechseln
cd beratungs-crm

# Dependencies installieren
composer install --optimize-autoloader --no-dev
npm install

# Assets für Produktion kompilieren
npm run build
```

#### 1.2 Konfiguration optimieren
```bash
# Laravel für Produktion optimieren
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Schritt 2: Fly.io Konfiguration

#### 2.1 Fly App initialisieren
```bash
# Fly App erstellen
flyctl apps create beratungs-crm

# Oder mit spezifischem Namen
flyctl apps create ihr-app-name
```

#### 2.2 fly.toml erstellen
Erstellen Sie eine `fly.toml` Datei im Projektroot:

```toml
# fly.toml app configuration file generated for beratungs-crm

app = "beratungs-crm"
primary_region = "fra"  # Frankfurt für deutsche Nutzer

[build]

[env]
  APP_ENV = "production"
  LOG_CHANNEL = "stderr"
  LOG_LEVEL = "info"
  LOG_STDERR_FORMATTER = "Monolog\\Formatter\\JsonFormatter"
  SESSION_DRIVER = "database"
  SESSION_SECURE_COOKIE = "true"
  CACHE_STORE = "database"
  QUEUE_CONNECTION = "database"

[experimental]
  auto_rollback = true

[[services]]
  http_checks = []
  internal_port = 8080
  processes = ["app"]
  protocol = "tcp"
  script_checks = []
  [services.concurrency]
    hard_limit = 25
    soft_limit = 20
    type = "connections"

  [[services.ports]]
    force_https = true
    handlers = ["http"]
    port = 80

  [[services.ports]]
    handlers = ["tls", "http"]
    port = 443

  [[services.tcp_checks]]
    grace_period = "1s"
    interval = "15s"
    restart_limit = 0
    timeout = "2s"

[[statics]]
  guest_path = "/app/public"
  url_prefix = "/"
```

#### 2.3 Dockerfile erstellen
Erstellen Sie ein `Dockerfile` im Projektroot:

```dockerfile
# Dockerfile für Laravel auf Fly.io
FROM php:8.2-fpm-alpine

# System dependencies installieren
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite \
    sqlite-dev \
    nodejs \
    npm \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip

# PHP Extensions installieren
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo \
        pdo_sqlite \
        pdo_mysql \
        bcmath \
        opcache

# Composer installieren
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Arbeitsverzeichnis setzen
WORKDIR /app

# Composer Dependencies kopieren und installieren
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Package.json kopieren und NPM Dependencies installieren
COPY package*.json ./
RUN npm ci --only=production

# Anwendungscode kopieren
COPY . .

# Assets kompilieren
RUN npm run build

# Laravel optimieren
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Berechtigungen setzen
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage \
    && chmod -R 755 /app/bootstrap/cache

# Nginx Konfiguration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/http.d/default.conf

# Supervisor Konfiguration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# PHP-FPM Konfiguration
RUN echo "listen = 127.0.0.1:9000" >> /usr/local/etc/php-fpm.d/www.conf

# Port freigeben
EXPOSE 8080

# Startup Script
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
```

#### 2.4 Docker Konfigurationsdateien erstellen

Erstellen Sie einen `docker/` Ordner mit folgenden Dateien:

**docker/nginx.conf:**
```nginx
user www-data;
worker_processes auto;
pid /run/nginx.pid;

events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    log_format main '$remote_addr - $remote_user [$time_local] "$request" '
                    '$status $body_bytes_sent "$http_referer" '
                    '"$http_user_agent" "$http_x_forwarded_for"';

    access_log /var/log/nginx/access.log main;
    error_log /var/log/nginx/error.log;

    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    types_hash_max_size 2048;

    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    include /etc/nginx/http.d/*.conf;
}
```

**docker/default.conf:**
```nginx
server {
    listen 8080;
    server_name _;
    root /app/public;
    index index.php;

    client_max_body_size 10M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

**docker/supervisord.conf:**
```ini
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid

[program:nginx]
command=nginx -g "daemon off;"
autostart=true
autorestart=true
stderr_logfile=/var/log/nginx/error.log
stdout_logfile=/var/log/nginx/access.log

[program:php-fpm]
command=php-fpm
autostart=true
autorestart=true
stderr_logfile=/var/log/php-fpm.log
stdout_logfile=/var/log/php-fpm.log

[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /app/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/worker.log
```

**docker/start.sh:**
```bash
#!/bin/sh

# Warten auf Datenbank (falls externe DB verwendet wird)
# php artisan wait-for-db

# Migrationen ausführen
php artisan migrate --force

# Storage Link erstellen
php artisan storage:link

# Supervisor starten
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
```

### Schritt 3: Datenbank konfigurieren

#### 3.1 PostgreSQL Datenbank erstellen
```bash
# PostgreSQL Datenbank auf Fly.io erstellen
flyctl postgres create --name beratungs-crm-db --region fra

# Datenbank-URL abrufen
flyctl postgres connect -a beratungs-crm-db
```

#### 3.2 Datenbank-Secrets setzen
```bash
# Datenbank-URL als Secret setzen
flyctl secrets set DATABASE_URL="postgres://username:password@hostname:port/database"

# Weitere Secrets setzen
flyctl secrets set APP_KEY="$(php artisan --no-ansi key:generate --show)"
flyctl secrets set APP_ENV="production"
flyctl secrets set APP_DEBUG="false"
flyctl secrets set APP_URL="https://ihr-app-name.fly.dev"
```

### Schritt 4: Umgebungsvariablen konfigurieren

#### 4.1 Produktions-.env erstellen
Erstellen Sie eine `.env.production` Datei:

```env
APP_NAME="Beratungs-CRM"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://ihr-app-name.fly.dev

LOG_CHANNEL=stderr
LOG_LEVEL=info

DB_CONNECTION=pgsql
DB_HOST=your-db-host.fly.dev
DB_PORT=5432
DB_DATABASE=your-database-name
DB_USERNAME=your-username
DB_PASSWORD=your-password

BROADCAST_DRIVER=log
CACHE_DRIVER=database
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

#### 4.2 Alle Secrets setzen
```bash
# Alle Umgebungsvariablen als Secrets setzen
flyctl secrets set \
  APP_NAME="Beratungs-CRM" \
  APP_ENV="production" \
  APP_DEBUG="false" \
  APP_URL="https://ihr-app-name.fly.dev" \
  DB_CONNECTION="pgsql" \
  MAIL_MAILER="smtp" \
  MAIL_HOST="smtp.gmail.com" \
  MAIL_PORT="587" \
  MAIL_USERNAME="your-email@gmail.com" \
  MAIL_PASSWORD="your-app-password" \
  MAIL_ENCRYPTION="tls" \
  MAIL_FROM_ADDRESS="your-email@gmail.com"
```

### Schritt 5: Deployment durchführen

#### 5.1 Erste Bereitstellung
```bash
# App deployen
flyctl deploy

# Status überprüfen
flyctl status

# Logs anzeigen
flyctl logs
```

#### 5.2 Datenbank initialisieren
```bash
# SSH-Verbindung zur App
flyctl ssh console

# In der App-Konsole:
php artisan migrate --force
php artisan db:seed --force
exit
```

### Schritt 6: Domain und SSL konfigurieren

#### 6.1 Custom Domain hinzufügen (optional)
```bash
# Custom Domain hinzufügen
flyctl certs create your-domain.com

# DNS-Einträge überprüfen
flyctl certs show your-domain.com
```

#### 6.2 DNS-Konfiguration
Fügen Sie folgende DNS-Einträge bei Ihrem Domain-Provider hinzu:
```
A     @     213.188.195.52
AAAA  @     2a09:8280:1::4:2
```

### Schritt 7: Monitoring und Wartung

#### 7.1 Logs überwachen
```bash
# Live-Logs anzeigen
flyctl logs -f

# Logs der letzten Stunde
flyctl logs --since=1h
```

#### 7.2 App-Metriken
```bash
# App-Status
flyctl status

# Ressourcenverbrauch
flyctl metrics
```

#### 7.3 Skalierung
```bash
# Vertikale Skalierung (mehr RAM/CPU)
flyctl scale vm shared-cpu-2x --memory 1024

# Horizontale Skalierung (mehr Instanzen)
flyctl scale count 2
```

## 🔧 Wartung und Updates

### Updates deployen
```bash
# Code-Änderungen committen
git add .
git commit -m "Update: Beschreibung der Änderungen"

# Neue Version deployen
flyctl deploy

# Rollback bei Problemen
flyctl releases list
flyctl rollback <release-version>
```

### Datenbank-Backups
```bash
# Backup erstellen
flyctl postgres backup create -a beratungs-crm-db

# Backups auflisten
flyctl postgres backup list -a beratungs-crm-db

# Backup wiederherstellen
flyctl postgres backup restore <backup-id> -a beratungs-crm-db
```

### Performance-Optimierung
```bash
# Redis für Caching hinzufügen
flyctl redis create --name beratungs-crm-redis

# Redis-URL als Secret setzen
flyctl secrets set REDIS_URL="redis://default:password@hostname:port"
```

## 🚨 Troubleshooting

### Häufige Probleme

#### 1. App startet nicht
```bash
# Logs überprüfen
flyctl logs

# SSH-Verbindung für Debugging
flyctl ssh console
```

#### 2. Datenbank-Verbindungsfehler
```bash
# Datenbank-Status überprüfen
flyctl postgres status -a beratungs-crm-db

# Verbindung testen
flyctl postgres connect -a beratungs-crm-db
```

#### 3. Assets werden nicht geladen
- Überprüfen Sie die Nginx-Konfiguration
- Stellen Sie sicher, dass `npm run build` ausgeführt wurde
- Überprüfen Sie die Asset-URLs in der .env

#### 4. Speicher-Probleme
```bash
# Mehr Speicher zuweisen
flyctl scale vm shared-cpu-1x --memory 512

# Oder größere VM
flyctl scale vm shared-cpu-2x --memory 1024
```

### Debug-Modus aktivieren (nur temporär!)
```bash
# Debug temporär aktivieren
flyctl secrets set APP_DEBUG="true"

# Nach dem Debugging wieder deaktivieren
flyctl secrets set APP_DEBUG="false"
```

## 💰 Kosten-Optimierung

### Ressourcen-Tipps
- **Shared CPU**: Ausreichend für kleine bis mittlere Anwendungen
- **Memory**: 256MB für Start, bei Bedarf erhöhen
- **PostgreSQL**: Shared-CPU-1x für Entwicklung, dedicated für Produktion
- **Volumes**: Nur bei Bedarf für persistente Dateien

### Monitoring der Kosten
```bash
# Aktuelle Nutzung anzeigen
flyctl dashboard

# Billing-Informationen
flyctl billing
```

## 📞 Support

### Fly.io Community
- **Forum**: https://community.fly.io/
- **Discord**: https://fly.io/discord
- **Dokumentation**: https://fly.io/docs/

### Laravel-spezifische Ressourcen
- **Laravel Deployment Guide**: https://fly.io/docs/laravel/
- **Laravel Community**: https://laracasts.com/discuss

---

**Erstellt**: 26. Juni 2025  
**Letzte Aktualisierung**: 26. Juni 2025  
**Version**: 1.0
