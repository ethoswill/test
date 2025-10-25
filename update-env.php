<?php

/**
 * Script to update .env file with Medusa API key
 * Run this after you get your API key from Medusa admin
 */

echo "🔑 Update .env with Medusa API Key\n";
echo "==================================\n\n";

// Get API key from user
echo "Enter your Medusa API key (or press Enter to skip): ";
$apiKey = trim(fgets(STDIN));

if (empty($apiKey)) {
    echo "⏭️  Skipped. You can update .env manually later.\n";
    exit(0);
}

// Validate API key format
if (!preg_match('/^pk_/', $apiKey) && !preg_match('/^sk_/', $apiKey)) {
    echo "⚠️  Warning: API key doesn't look like a standard Medusa key\n";
    echo "   (Should start with 'pk_' or 'sk_')\n";
    echo "   Continue anyway? (y/N): ";
    $confirm = trim(fgets(STDIN));
    if (strtolower($confirm) !== 'y') {
        echo "❌ Cancelled.\n";
        exit(0);
    }
}

// Update .env file
$envFile = "/Users/williamhunt/my-app/.env";
if (!file_exists($envFile)) {
    echo "❌ Error: .env file not found at $envFile\n";
    exit(1);
}

$envContent = file_get_contents($envFile);

// Replace the API key
$updated = false;
if (strpos($envContent, 'MEDUSA_API_KEY=your-api-key-here') !== false) {
    $envContent = str_replace('MEDUSA_API_KEY=your-api-key-here', "MEDUSA_API_KEY=$apiKey", $envContent);
    $updated = true;
} elseif (preg_match('/MEDUSA_API_KEY=.*/', $envContent)) {
    $envContent = preg_replace('/MEDUSA_API_KEY=.*/', "MEDUSA_API_KEY=$apiKey", $envContent);
    $updated = true;
} else {
    // Add the API key if it doesn't exist
    $envContent .= "\nMEDUSA_API_KEY=$apiKey\n";
    $updated = true;
}

if ($updated) {
    file_put_contents($envFile, $envContent);
    echo "✅ .env file updated successfully!\n";
    echo "🔑 API Key: $apiKey\n\n";
    
    echo "🎉 Next steps:\n";
    echo "1. Restart your Laravel server (if running)\n";
    echo "2. Go to: http://localhost:8000/admin/stores\n";
    echo "3. Click 'Sync All Stores' to test!\n\n";
    
    echo "✅ Your sync will now work with:\n";
    echo "   - Create new products in Medusa (first sync)\n";
    echo "   - Update existing products in Medusa (subsequent syncs)\n";
    echo "   - Smart detection of existing products\n";
    echo "   - Clear success/error messages\n";
} else {
    echo "❌ Failed to update .env file\n";
}





