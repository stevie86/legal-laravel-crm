# --- Composer Stage ---
FROM composer:2.7 AS composer

# --- Node Build Stage ---
FROM node:20-alpine AS nodebuild
WORKDIR /app
COPY package*.json ./
RUN npm ci --omit=dev || npm install --omit=dev
COPY resources/ ./resources/
COPY vite.config.js ./
COPY public/ ./public/
RUN npm run build || npm run prod || true

# --- Main Stage ---
ARG CACHEBUST=1
FROM php:8.2-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite \
    sqlite-dev \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip

# PHP Extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo \
        pdo_sqlite \
        pdo_mysql \
        bcmath \
        opcache

# Composer installieren
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Nur die für Composer nötigen Dateien kopieren
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Anwendungscode kopieren (ohne node_modules, ohne .env)
COPY app/ ./app/
COPY bootstrap/ ./bootstrap/
COPY config/ ./config/
COPY database/ ./database/
COPY public/ ./public/
COPY resources/ ./resources/
COPY routes/ ./routes/
COPY artisan ./
COPY vendor/ ./vendor/

# Gebaute Assets aus Node-Stage übernehmen (Passe ggf. den Pfad an!)
COPY --from=nodebuild /app/public/build ./public/build

# Laravel optimieren
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Berechtigungen setzen
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage \
    && chmod -R 755 /app/bootstrap/cache

# Nginx/Supervisor Konfiguration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# PHP-FPM Konfiguration
RUN echo "listen = 127.0.0.1:9000" >> /usr/local/etc/php-fpm.d/www.conf

EXPOSE 8080

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]