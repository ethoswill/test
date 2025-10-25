#!/bin/bash

echo "=== LARAVEL STAGE 1: Basic Laravel without Database ==="

# Wait for any potential database
echo "Waiting for services to be ready..."
sleep 10

# Try to run migrations (but don't fail if they don't work)
echo "Attempting database setup..."
php artisan migrate --force 2>/dev/null || echo "Database not available, continuing without it..."

# Clear caches
echo "Clearing Laravel caches..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Restore original Laravel index.php
echo "Restoring Laravel index.php..."
cp backup-original-index.php public/index.php

# Start Laravel with artisan serve
echo "Starting Laravel with artisan serve on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
