# Dockerfile
FROM php:8.4-fpm-alpine

# ──────────────────────────────────────────────────────────────
# 1. System packages (incl. Node.js + npm)
# ──────────────────────────────────────────────────────────────
RUN apk add --no-cache \
    git curl zip unzip \
    supervisor \
    oniguruma-dev libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev libxml2-dev \
    mysql-client bash \
    nodejs npm

# ──────────────────────────────────────────────────────────────
# 2. PHP extensions
# ──────────────────────────────────────────────────────────────
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring bcmath exif pcntl gd

# Redis
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ──────────────────────────────────────────────────────────────
# 3. Working directory
# ──────────────────────────────────────────────────────────────
WORKDIR /var/www

# ──────────────────────────────────────────────────────────────
# 4. COPY ENTIRE PROJECT FIRST
# ──────────────────────────────────────────────────────────────
COPY . /var/www

# ──────────────────────────────────────────────────────────────
# 5. Install dependencies + Laravel setup
# ──────────────────────────────────────────────────────────────
ARG APP_ENV=local

# Use cache mounts for faster repeated builds (optional but recommended)
RUN --mount=type=cache,target=/root/.composer \
    --mount=type=cache,target=/root/.npm \
    \
    # Composer
    if [ "$APP_ENV" = "production" ]; then \
        composer install --no-dev --optimize-autoloader --classmap-authoritative --no-interaction; \
    else \
        composer install --optimize-autoloader --no-interaction; \
    fi \
    \
    # Node
    && npm ci \
    \
    # Laravel
    && php artisan migrate --seed --force \
    \
    # Build assets (only in production)
    && npm run build

# ──────────────────────────────────────────────────────────────
# 6. Permissions
# ──────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]