#!/bin/bash

echo "=== PRODUCTION STARTUP ==="

# Ensure Laravel index is in place
if [ ! -f public/index.php ]; then
    cp backup-original-index.php public/index.php
fi

# Clear any caches
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "Starting Laravel app with PHP built-in server..."
exec php -S 0.0.0.0:$PORT -t public
