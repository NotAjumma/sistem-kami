#!/bin/sh

# Optional: print start info
echo "Starting container and running drive:download-receipts..."

# Run your Laravel artisan command to download receipts from Google Drive
php artisan drive:download-receipts

# Start Laravel development server (adjust if you use something else)
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
