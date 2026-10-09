#!/bin/sh
set -e

# Wait for DB to be ready (extra safety beyond healthcheck)
echo "Waiting for database..."
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
  sleep 2
done
echo "Database ready."

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi

# Cache config for performance (route:cache skipped — duplicate route name conflict)
php artisan config:cache

# Storage symlink
php artisan storage:link --quiet 2>/dev/null || true

# Set permissions
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
