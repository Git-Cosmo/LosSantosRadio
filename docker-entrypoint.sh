#!/bin/bash
set -e

# Use the .env.docker as .env
if [ -f /var/www/.env.docker ]; then
    cp /var/www/.env.docker /var/www/.env
fi

# Run migrations safely (only if DB is ready)
echo "Waiting for database..."
MAX_TRIES=10
TRIES=0
until php artisan migrate --force; do
  TRIES=$((TRIES+1))
  if [ $TRIES -ge $MAX_TRIES ]; then
    echo "Database not ready after $MAX_TRIES tries, exiting."
    exit 1
  fi
  echo "Database not ready, retrying in 5 seconds..."
  sleep 5
done

# Start Supervisor (runs Nginx + PHP-FPM)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
