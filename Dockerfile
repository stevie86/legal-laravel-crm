# Dockerfile for a production Laravel application on Fly.io

# 1. Builder stage for PHP dependencies
FROM composer:2 as composer_vendor

WORKDIR /app
COPY . .
# Install dependencies, optimizing for production
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader


# 2. Builder stage for frontend assets
FROM node:18-alpine as node_assets

WORKDIR /app
COPY . .
# Install and build assets for production
RUN npm install && npm run build


# 3. Final production image
FROM php:8.2-fpm-alpine as production

# Install system dependencies required by Laravel and Nginx
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    zip \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    # For PostgreSQL
    postgresql-dev

# Install required PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    pdo \
    pdo_pgsql \
    exif \
    bcmath

# Set up a non-root user
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

# Copy configuration files from your repository into the image
COPY .fly/nginx.conf /etc/nginx/nginx.conf
COPY .fly/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY .fly/php.ini /usr/local/etc/php/conf.d/php.ini
COPY .fly/www.conf /usr/local/etc/php-fpm.d/www.conf

# Set working directory
WORKDIR /var/www/html

# Copy application code and built assets from builder stages
COPY --from=composer_vendor /app/vendor ./vendor
COPY --from=node_assets /app/public ./public
COPY . .

# Set correct permissions for storage and bootstrap/cache
RUN chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 8080 for Nginx
EXPOSE 8080

# Entrypoint script to run optimizations and start services
COPY .fly/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]