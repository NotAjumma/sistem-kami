#!/bin/bash
set -e

echo "==> Running post-deploy health check..."
php artisan health:report --force || true   # send email regardless of pass/fail on deploy

echo "==> Starting web server..."
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
