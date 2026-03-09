#!/bin/sh

echo "Starting container..."

php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

php artisan storage:link --force 2>/dev/null || true

# Generate WebP versions for existing uploaded images (skips already-converted files)
php artisan images:optimize --quiet 2>/dev/null || true

echo "Running migrations..."
php artisan migrate --force

# Cache config/routes/views for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run Laravel scheduler in background (fires every minute)
(while true; do php artisan schedule:run >> /dev/null 2>&1; sleep 60; done) &

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g 'daemon off;'
