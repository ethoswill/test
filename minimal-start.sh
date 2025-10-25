#!/bin/bash

echo "Starting Laravel application with database..."

# Wait for database to be ready
echo "Waiting for database connection..."
sleep 10

# Try to run migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migrations failed, continuing anyway..."

# Start PHP's built-in server
echo "Starting PHP server..."
exec php -S 0.0.0.0:$PORT -t public
