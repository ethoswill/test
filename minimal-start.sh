#!/bin/bash

echo "Starting Laravel application with database..."

# Wait for database to be ready
echo "Waiting for database connection..."
sleep 15

# Try to run migrations with better error handling
echo "Running database migrations..."
if php artisan migrate --force; then
    echo "Migrations completed successfully!"
else
    echo "Migrations failed, but continuing..."
fi

# Clear any caches
echo "Clearing caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Start PHP's built-in server with proper error handling
echo "Starting PHP server on port $PORT..."
exec php -S 0.0.0.0:$PORT -t public
