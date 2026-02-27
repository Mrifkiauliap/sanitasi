#!/bin/sh
set -e

# Wait for DB
echo "Waiting for database..."
while ! nc -z db 3306; do
  sleep 3
done
echo "Database is up!"

# Ensure permissions for storage and cache
echo "Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Run migrations (safe for production)
echo "Running migrations..."
php artisan migrate --force

# Optimize Laravel (config, routes, views)
echo "Optimizing Laravel..."
php artisan optimize

# Start Nginx in background
nginx -g 'daemon off;' &

# Start PHP-FPM in foreground
echo "Starting PHP-FPM..."
php-fpm
