#!/usr/bin/env php
<?php

/**
 * Helper script to set up Google API Key for Sheets import
 */

echo "\n🔑 Google Sheets API Key Setup\n";
echo "================================\n\n";

// Get API key from user
echo "Enter your Google API Key (or press Enter to use the key already in .env): ";
$apiKey = trim(fgets(STDIN));

if (empty($apiKey)) {
    echo "⏭️  Skipping. You can update .env manually.\n";
    exit(0);
}

// Validate API key format (Google API keys are typically long strings)
if (strlen($apiKey) < 30) {
    echo "⚠️  Warning: API key seems too short\n";
}

// Update .env file
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    echo "❌ Error: .env file not found\n";
    exit(1);
}

$envContent = file_get_contents($envFile);

// Replace or add the GOOGLE_API_KEY
if (strpos($envContent, 'GOOGLE_API_KEY=your-google-api-key-here') !== false) {
    $envContent = str_replace(
        'GOOGLE_API_KEY=your-google-api-key-here',
        "GOOGLE_API_KEY=$apiKey",
        $envContent
    );
} elseif (preg_match('/GOOGLE_API_KEY=.*/', $envContent)) {
    $envContent = preg_replace(
        '/GOOGLE_API_KEY=.*/',
        "GOOGLE_API_KEY=$apiKey",
        $envContent
    );
} else {
    // Add the API key if it doesn't exist
    $envContent .= "\n# Google Sheets API Configuration\n";
    $envContent .= "GOOGLE_API_KEY=$apiKey\n";
}

file_put_contents($envFile, $envContent);

echo "✅ Google API Key added to .env file!\n";
echo "🔑 Key: " . substr($apiKey, 0, 10) . "...\n\n";
echo "🎉 Your Google Sheets import should now work!\n\n";
echo "📝 Next steps:\n";
echo "1. Go to your admin panel: http://127.0.0.1:8001/admin\n";
echo "2. Navigate to Products\n";
echo "3. Click 'Sync from Google Sheets'\n";
echo "4. Enter your Google Sheet URL and enjoy!\n\n";

