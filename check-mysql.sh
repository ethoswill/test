#!/bin/bash

echo "🔍 Checking MySQL connection status..."
echo ""

# Check if port 3306 is accessible
if nc -zv 127.0.0.1 3306 2>&1 | grep -q "succeeded"; then
    echo "✅ MySQL is RUNNING on port 3306!"
    echo ""
    echo "Testing database connection..."
    if php artisan db:show 2>/dev/null; then
        echo ""
        echo "✅ Database connection successful!"
        exit 0
    else
        echo ""
        echo "⚠️  MySQL is running but database connection failed."
        echo "   This might mean:"
        echo "   - Database 'my_app_db' doesn't exist yet"
        echo "   - Wrong credentials in .env"
        echo ""
        echo "Run: php artisan migrate"
        exit 1
    fi
else
    echo "❌ MySQL is NOT RUNNING!"
    echo ""
    echo "📋 To fix this:"
    echo ""
    echo "1. Open DBngin application:"
    echo "   - Press Cmd+Space, type 'DBngin', press Enter"
    echo "   - Or open from Applications folder"
    echo ""
    echo "2. In DBngin:"
    echo "   - Find 'MySQL' in the list"
    echo "   - Click the 'Start' button next to MySQL"
    echo "   - Wait for status to turn GREEN ✅"
    echo ""
    echo "3. Verify it's running:"
    echo "   ./check-mysql.sh"
    echo ""
    echo "4. Create database (first time only):"
    echo "   mysql -u root -e 'CREATE DATABASE IF NOT EXISTS my_app_db;'"
    echo ""
    echo "5. Run migrations:"
    echo "   php artisan migrate"
    echo ""
    
    # Try to find DBngin
    if [ -d "/Applications/DBngin.app" ]; then
        echo "💡 Found DBngin at: /Applications/DBngin.app"
        echo "   You can open it with: open /Applications/DBngin.app"
        echo ""
    fi
    
    # Try to find mysql command
    MYSQL_PATH=$(find /Applications/DBngin.app -name mysql 2>/dev/null | head -1)
    if [ -n "$MYSQL_PATH" ]; then
        echo "💡 Found MySQL binary at: $MYSQL_PATH"
        echo "   Use it like: $MYSQL_PATH -u root -e 'CREATE DATABASE my_app_db;'"
        echo ""
    fi
    
    exit 1
fi
