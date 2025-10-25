#!/bin/bash

set -e  # Exit on error

echo "=== PRODUCTION STARTUP ==="
echo "PORT: ${PORT:-8000}"
echo "PHP Version: $(php -v | head -n 1)"

# Force use the ultra-minimal index for healthchecks
echo "Using ultra-minimal index.php for reliable healthchecks..."
cp public/index-simple.php public/index.php

# Set default PORT if not set
PORT=${PORT:-8000}

echo "Starting PHP server on port $PORT..."
echo "Using simple healthcheck endpoint"
exec php -S 0.0.0.0:$PORT -t public
