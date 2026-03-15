#!/bin/sh

echo "Starting worker service..."

# Cache config/routes/views for production
php artisan config:cache
php artisan route:cache 2>/dev/null || true
php artisan view:cache

# Higher memory limit for worker (PDF, Excel, image processing)
echo "memory_limit=1024M" > /usr/local/etc/php/conf.d/memory-worker.ini

# Start scheduler in background
echo "Starting scheduler..."
php artisan schedule:work &

# Start queue worker in foreground
echo "Starting queue worker..."
php artisan queue:work redis \
    --tries=3 \
    --timeout=300 \
    --memory=512 \
    --sleep=3 \
    --max-jobs=500 \
    --queue=high,default,low
