#!/bin/bash

echo "Starting ultra-simple PHP server..."

# Copy the simple index file to be the main index
cp public/index-simple.php public/index.php

# Start PHP server
exec php -S 0.0.0.0:$PORT -t public
