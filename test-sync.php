<?php

/**
 * Script to test the Medusa sync functionality
 * Run this after setting up the API key
 */

require_once 'vendor/autoload.php';

echo "🧪 Testing Medusa Sync Functionality\n";
echo "====================================\n\n";

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check if API key is set
$apiKey = $_ENV['MEDUSA_API_KEY'] ?? null;

if (empty($apiKey) || $apiKey === 'your-api-key-here') {
    echo "❌ Error: MEDUSA_API_KEY not set in .env file\n";
    echo "Please run: php update-env.php\n";
    exit(1);
}

echo "✅ API Key found: " . substr($apiKey, 0, 10) . "...\n\n";

// Test connection to Medusa
echo "🔍 Testing connection to Medusa...\n";
$medusaUrl = $_ENV['MEDUSA_BASE_URL'] ?? 'http://localhost:9000';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$medusaUrl/admin/products");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Connection to Medusa successful!\n";
    echo "🎉 Your API key is working perfectly!\n\n";
    
    echo "🚀 Ready to sync! You can now:\n";
    echo "1. Go to: http://localhost:8000/admin/stores\n";
    echo "2. Click 'Sync All Stores'\n";
    echo "3. Watch products get created/updated in Medusa!\n\n";
    
    echo "✅ Sync features:\n";
    echo "   - Create new products in Medusa (first sync)\n";
    echo "   - Update existing products in Medusa (subsequent syncs)\n";
    echo "   - Smart detection of existing products\n";
    echo "   - Clear success/error messages\n";
    
} elseif ($httpCode === 401) {
    echo "❌ Authentication failed (401)\n";
    echo "Please check your API key in .env file\n";
    echo "Run: php update-env.php\n";
} elseif ($httpCode === 404) {
    echo "❌ API endpoint not found (404)\n";
    echo "Please check if Medusa is running on port 9000\n";
} else {
    echo "❌ Connection failed (HTTP $httpCode)\n";
    echo "Response: " . substr($response, 0, 200) . "...\n";
}

echo "\n📚 For detailed instructions, see: MEDUSA_API_SETUP.md\n";





