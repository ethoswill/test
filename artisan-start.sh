#!/bin/bash

echo "Starting Laravel application with artisan serve..."

# Wait for database to be ready
echo "Waiting for database connection..."
sleep 15

# Try to run migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migrations failed, continuing anyway..."

# Clear caches
echo "Clearing caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Start Laravel with artisan serve
echo "Starting Laravel with artisan serve on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
