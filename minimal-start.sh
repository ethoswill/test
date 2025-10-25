#!/bin/bash

echo "Starting minimal PHP server..."

# Start PHP's built-in server directly on the public directory
exec php -S 0.0.0.0:$PORT -t public
