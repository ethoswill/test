<?php

/**
 * Script to help get Medusa API key
 * This will guide you through the process
 */

echo "🔑 Medusa API Key Setup Guide\n";
echo "=============================\n\n";

// Check if Medusa is running
$medusaUrl = "http://localhost:9000";
$response = @file_get_contents("$medusaUrl/app");

if ($response === false) {
    echo "❌ Error: Medusa is not running on port 9000\n";
    echo "Please start Medusa first: cd ethos-test && npm run dev\n";
    exit(1);
}

echo "✅ Medusa is running on port 9000\n\n";

// Open Medusa admin in browser
echo "🌐 Opening Medusa admin interface...\n";
exec("open 'http://localhost:9000/app'");

echo "📋 Follow these steps:\n";
echo "=====================\n\n";

echo "1. Login to Medusa admin:\n";
echo "   - Email: admin@example.com\n";
echo "   - Password: password123\n\n";

echo "2. Navigate to API Keys:\n";
echo "   - Click on Settings (gear icon) in the sidebar\n";
echo "   - Look for 'API Key Management' or 'API Keys'\n";
echo "   - Click 'Create API Key'\n\n";

echo "3. Create the API Key:\n";
echo "   - Name: Laravel Sync\n";
echo "   - Type: Publishable (or Admin if available)\n";
echo "   - Click 'Create'\n\n";

echo "4. Copy the API Key:\n";
echo "   - Copy the generated token\n";
echo "   - It will look like: pk_1234567890abcdef...\n\n";

echo "5. Update your .env file:\n";
echo "   - Open: /Users/williamhunt/my-app/.env\n";
echo "   - Find: MEDUSA_API_KEY=your-api-key-here\n";
echo "   - Replace with: MEDUSA_API_KEY=your-actual-api-key\n\n";

echo "6. Test the sync:\n";
echo "   - Go to: http://localhost:8000/admin/stores\n";
echo "   - Click 'Sync All Stores'\n";
echo "   - Watch products get created/updated in Medusa!\n\n";

echo "🎉 Once completed, your sync will work with:\n";
echo "   ✅ Create new products in Medusa (first sync)\n";
echo "   ✅ Update existing products in Medusa (subsequent syncs)\n";
echo "   ✅ Smart detection of existing products\n";
echo "   ✅ Clear success/error messages\n\n";

// Check current .env
$envFile = "/Users/williamhunt/my-app/.env";
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'MEDUSA_API_KEY=your-api-key-here') !== false) {
        echo "⚠️  Current .env still has placeholder API key\n";
        echo "   Please update it with your actual API key\n\n";
    } else {
        echo "✅ .env file may already have API key configured\n\n";
    }
}

echo "📚 For detailed instructions, see: MEDUSA_API_SETUP.md\n";





