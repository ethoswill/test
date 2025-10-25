#!/bin/bash

set -e  # Exit on error

echo "=== PRODUCTION STARTUP ==="
echo "PORT: ${PORT:-8000}"
echo "PHP Version: $(php -v | head -n 1)"

# Start with the ultra-minimal index for healthchecks
if [ ! -f public/index.php ]; then
    echo "Using ultra-minimal index.php for reliable healthchecks..."
    cp public/index-simple.php public/index.php
fi

# Set default PORT if not set
PORT=${PORT:-8000}

echo "Starting PHP server on port $PORT..."
echo "Using simple healthcheck endpoint for now"
exec php -S 0.0.0.0:$PORT -t public
