#!/bin/bash

# Setup script for MySQL database using DBngin
# This script configures the Laravel application to use MySQL

echo "🔧 Setting up MySQL database with DBngin..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.example..."
    cp .env.example .env
    php artisan key:generate
fi

# Update .env to use MySQL
echo "📝 Updating .env to use MySQL..."
sed -i.bak 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
sed -i.bak 's/^# DB_HOST=/DB_HOST=/' .env
sed -i.bak 's/^# DB_PORT=/DB_PORT=/' .env
sed -i.bak 's/^# DB_DATABASE=/DB_DATABASE=/' .env
sed -i.bak 's/^# DB_USERNAME=/DB_USERNAME=/' .env
sed -i.bak 's/^# DB_PASSWORD=/DB_PASSWORD=/' .env

# Set default database name if not set
if ! grep -q "DB_DATABASE=" .env || grep -q "^# DB_DATABASE=" .env; then
    echo "DB_DATABASE=my_app_db" >> .env
fi

# Get database name from .env
DB_NAME=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2 | tr -d ' ')

echo "📦 Database configuration:"
echo "   Database: $DB_NAME"
echo "   Host: 127.0.0.1"
echo "   Port: 3306"
echo "   Username: root"
echo ""

# Check if MySQL is accessible
echo "🔍 Checking MySQL connection..."
if php artisan db:show 2>/dev/null; then
    echo "✅ MySQL connection successful!"
else
    echo "⚠️  MySQL connection failed. Please ensure:"
    echo "   1. DBngin is running"
    echo "   2. MySQL server is started in DBngin"
    echo "   3. Database '$DB_NAME' exists (or will be created during migration)"
    echo ""
    echo "💡 To create the database manually:"
    echo "   Open DBngin, start MySQL, then run:"
    echo "   mysql -u root -e \"CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\""
    echo ""
fi

# Clear config cache
echo "🧹 Clearing configuration cache..."
php artisan config:clear

echo ""
echo "✨ Setup complete!"
echo ""
echo "📋 Next steps:"
echo "   1. Ensure MySQL is running in DBngin"
echo "   2. Create the database if it doesn't exist:"
echo "      mysql -u root -e \"CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\""
echo "   3. Run migrations:"
echo "      php artisan migrate"
echo ""
