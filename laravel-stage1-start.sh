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

# Ensure we have the correct Laravel index.php
echo "Ensuring Laravel index.php is correct..."
if [ ! -f "public/index.php" ] || ! grep -q "LARAVEL_START" public/index.php; then
    echo "Restoring Laravel index.php from backup..."
    cp backup-original-index.php public/index.php
else
    echo "Laravel index.php already correct"
fi

# Verify the file
echo "Verifying index.php content..."
head -5 public/index.php

# Start Laravel with artisan serve
echo "Starting Laravel with artisan serve on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
