#!/bin/sh

echo "Starting web service..."

php artisan optimize:clear
php artisan storage:link --force 2>/dev/null || true

echo "Running migrations..."
php artisan migrate --force --isolated

# Cache config/routes/views for production
php artisan config:cache
php artisan route:cache 2>/dev/null || echo "Warning: route:cache failed, continuing without route cache"
php artisan view:cache

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g 'daemon off;'
