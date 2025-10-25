<?php

/**
 * Script to automatically set up Medusa API key
 * This script will help you get the API key and update your .env file
 */

echo "🔑 Setting up Medusa API Key for Laravel Sync\n";
echo "============================================\n\n";

// Check if Medusa is running
$medusaUrl = "http://localhost:9000";
$response = @file_get_contents("$medusaUrl/app");

if ($response === false) {
    echo "❌ Error: Medusa is not running on port 9000\n";
    echo "Please start Medusa first: cd ethos-test && npm run dev\n";
    exit(1);
}

echo "✅ Medusa is running on port 9000\n\n";

// Check if admin user exists
echo "🔍 Checking if admin user exists...\n";
$adminCheck = @file_get_contents("$medusaUrl/admin/users");
if ($adminCheck === false) {
    echo "❌ Error: Cannot access Medusa admin API\n";
    echo "Please make sure Medusa is fully started and accessible\n";
    exit(1);
}

echo "✅ Medusa admin API is accessible\n\n";

// Instructions for manual setup
echo "📋 Manual Setup Instructions:\n";
echo "=============================\n\n";

echo "1. Open your browser and go to: http://localhost:9000/app\n";
echo "2. Login with:\n";
echo "   - Email: admin@example.com\n";
echo "   - Password: password123\n\n";

echo "3. Once logged in:\n";
echo "   - Click on Settings (gear icon) in the sidebar\n";
echo "   - Look for 'API Key Management' or 'API Keys'\n";
echo "   - Click 'Create API Key'\n";
echo "   - Name it: 'Laravel Sync'\n";
echo "   - Copy the generated API key\n\n";

echo "4. Update your .env file:\n";
echo "   - Open: /Users/williamhunt/my-app/.env\n";
echo "   - Find: MEDUSA_API_KEY=your-api-key-here\n";
echo "   - Replace with: MEDUSA_API_KEY=your-actual-api-key\n\n";

echo "5. Restart Laravel server:\n";
echo "   - Stop current server (Ctrl+C)\n";
echo "   - Run: php artisan serve\n\n";

echo "6. Test the sync:\n";
echo "   - Go to: http://localhost:8000/admin/stores\n";
echo "   - Click 'Sync All Stores'\n";
echo "   - Watch products get created/updated in Medusa!\n\n";

echo "🎉 Once you've completed these steps, your sync will work perfectly!\n";
echo "   - First sync: Creates new products in Medusa\n";
echo "   - Subsequent syncs: Updates existing products (overwrite save)\n\n";

// Check current .env file
$envFile = "/Users/williamhunt/my-app/.env";
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'MEDUSA_API_KEY=your-api-key-here') !== false) {
        echo "⚠️  Current .env still has placeholder API key\n";
        echo "   Please update it with your actual API key from Medusa admin\n\n";
    } else {
        echo "✅ .env file found and may already have API key configured\n\n";
    }
} else {
    echo "❌ .env file not found at: $envFile\n";
}

echo "📚 For detailed instructions, see: MEDUSA_API_SETUP.md\n";





