#!/bin/bash

# Exit on any error
set -e

echo "Starting application deployment..."

# Wait a moment for database to be ready
echo "Waiting for database connection..."
sleep 5

# Try to run migrations with retry logic
echo "Running database migrations..."
for i in {1..5}; do
    echo "Migration attempt $i/5..."
    if php artisan migrate --force; then
        echo "Migrations completed successfully!"
        break
    else
        echo "Migration attempt $i failed, retrying in 10 seconds..."
        sleep 10
    fi
done

# Clear caches
echo "Clearing application caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Start the application
echo "Starting Laravel application..."
php artisan serve --host=0.0.0.0 --port=$PORT
