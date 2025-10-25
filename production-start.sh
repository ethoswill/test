#!/bin/bash

echo "=== PRODUCTION STARTUP ==="

# Wait for database
echo "Waiting for database..."
sleep 10

# Clear caches
echo "Clearing caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Try to run migrations (but don't fail if DB not ready)
echo "Attempting migrations..."
php artisan migrate --force || echo "Migrations skipped (database may not be ready)"

# Start Laravel server
echo "Starting Laravel server on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
