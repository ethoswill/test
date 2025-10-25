#!/bin/bash

echo "Starting staged deployment approach..."

# Stage 1: Start with simple PHP to ensure basic connectivity
echo "Stage 1: Testing basic PHP connectivity..."

# Copy simple index file
cp public/index-simple.php public/index.php

# Start PHP server
echo "Starting PHP server on port $PORT..."
exec php -S 0.0.0.0:$PORT -t public
