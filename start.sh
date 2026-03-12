#!/bin/bash
set -e

echo "==> Running post-deploy health check..."
php artisan health:report --force || true   # send email regardless of pass/fail on deploy

echo "==> Starting Laravel scheduler in background..."
php artisan schedule:work &
SCHEDULER_PID=$!
echo "    Scheduler PID: $SCHEDULER_PID"

echo "==> Starting web server..."
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
