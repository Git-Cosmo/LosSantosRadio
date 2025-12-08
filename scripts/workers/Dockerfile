# syntax=docker/dockerfile:1
FROM php:8.4-cli-alpine

# Install system dependencies & PHP extensions
RUN apk add --no-cache \
        git \
        unzip \
        supervisor \
        libpq \
    && docker-php-ext-install -j$(nproc) \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
    && rm -rf /var/cache/apk/* /tmp/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (optimized for layers)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application code
COPY . .

# Generate autoloader
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative

# Create queue worker supervisor config
RUN mkdir -p /etc/supervisor/conf.d
COPY <<EOF /etc/supervisor/conf.d/queue-worker.conf
[program:queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/worker.log
stopwaitsecs=3600
EOF

# Create www-data user (consistent with typical web containers)
RUN adduser -D -u 1000 www-data && chown -R www-data:www-data /var/www/html

# Use non-root user
USER www-data

# Expose nothing (queue worker doesn't need ports)
# Run supervisor to manage queue worker
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/queue-worker.conf"]