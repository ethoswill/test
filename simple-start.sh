#!/bin/bash

echo "Starting Laravel application with minimal setup..."

# Just start the server - no migrations, no cache clearing
exec php artisan serve --host=0.0.0.0 --port=$PORT
