#!/bin/bash

# Don't exit on error - let the app start even if migrations fail
set +e

echo "Starting application deployment..."

# Wait a moment for database to be ready
echo "Waiting for database connection..."
sleep 10

# Try to run migrations (but don't fail if they don't work)
echo "Attempting database migrations..."
php artisan migrate --force || echo "Migrations failed, continuing anyway..."

# Clear caches
echo "Clearing application caches..."
php artisan config:clear || echo "Config clear failed"
php artisan route:clear || echo "Route clear failed"
php artisan view:clear || echo "View clear failed"

# Start the application
echo "Starting Laravel application..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
