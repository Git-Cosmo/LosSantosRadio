# ──────────────────────────────
# Stage 1: Build
# ──────────────────────────────
FROM php:8.4-fpm-alpine AS build

# Install build dependencies
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    libxml2-dev \
    oniguruma-dev \
    $PHPIZE_DEPS \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring bcmath pcntl gd opcache exif \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy full application first
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Install Node.js dependencies and build assets
RUN npm install && npm run build

# ──────────────────────────────
# Stage 2: Runtime
# ──────────────────────────────
FROM php:8.4-fpm-alpine

# Install runtime dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    libpng \
    libjpeg-turbo \
    libwebp \
    freetype \
    oniguruma \
    mysql-client \
    curl

# Install PHP extensions required for runtime (matching build stage)
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    libxml2-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring bcmath pcntl gd opcache exif \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

WORKDIR /var/www

# Copy built app and composer from build stage
COPY --from=build /var/www /var/www
COPY --from=build /usr/bin/composer /usr/bin/composer

# Copy configs
# Put nginx.conf into the correct http.d directory
COPY scripts/nginx.conf /etc/nginx/http.d/lossantosradio.conf
RUN rm -f /etc/nginx/http.d/default.conf

COPY scripts/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Enable OpCache
RUN echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Create Supervisor log directories
RUN mkdir -p /var/log/supervisor \
    && chown -R www-data:www-data /var/log/supervisor

# Create Nginx runtime directories
RUN mkdir -p /var/log/nginx /run/nginx \
    && chown -R www-data:www-data /var/log/nginx /run/nginx

EXPOSE 80
ENTRYPOINT ["docker-entrypoint.sh"]
