<?php

/**
 * Script to add Medusa database configuration to .env file
 */

echo "🔧 Adding Medusa Database Configuration\n";
echo "======================================\n\n";

// Update .env file
$envFile = "/Users/williamhunt/my-app/.env";
if (!file_exists($envFile)) {
    echo "❌ Error: .env file not found at $envFile\n";
    exit(1);
}

$envContent = file_get_contents($envFile);

// Add Medusa database configuration
$medusaDbConfig = "\n# Medusa Database Configuration\n";
$medusaDbConfig .= "MEDUSA_DB_HOST=127.0.0.1\n";
$medusaDbConfig .= "MEDUSA_DB_PORT=5432\n";
$medusaDbConfig .= "MEDUSA_DB_DATABASE=medusa\n";
$medusaDbConfig .= "MEDUSA_DB_USERNAME=postgres\n";
$medusaDbConfig .= "MEDUSA_DB_PASSWORD=password\n";
$medusaDbConfig .= "MEDUSA_DB_CHARSET=utf8\n";

// Check if Medusa DB config already exists
if (strpos($envContent, 'MEDUSA_DB_HOST') === false) {
    $envContent .= $medusaDbConfig;
    file_put_contents($envFile, $envContent);
    echo "✅ Medusa database configuration added to .env file\n";
    echo "🔧 Database: medusa\n";
    echo "🔧 Host: 127.0.0.1:5432\n";
    echo "🔧 Username: postgres\n\n";
} else {
    echo "✅ Medusa database configuration already exists in .env file\n\n";
}

echo "🎉 Next steps:\n";
echo "1. The sync will now create real products in Medusa database\n";
echo "2. Go to: http://localhost:8000/admin/stores\n";
echo "3. Click 'Sync All Stores' to test!\n";
echo "4. Check Medusa admin: http://localhost:9000/app/products\n\n";

echo "✅ Products will now appear in Medusa!\n";





