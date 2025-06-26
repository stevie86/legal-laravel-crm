#!/bin/sh

# Warten auf Datenbank (falls externe DB verwendet wird)
# php artisan wait-for-db

# Migrationen ausführen
php artisan migrate --force

# Storage Link erstellen
php artisan storage:link

# Supervisor starten
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf