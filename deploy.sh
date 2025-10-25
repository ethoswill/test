#!/bin/bash

echo "🚀 Starting deployment process..."

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

echo "✅ Deployment completed successfully!"
