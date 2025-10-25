<?php

/**
 * Script to update Medusa database configuration with correct credentials
 */

echo "🔧 Updating Medusa Database Configuration\n";
echo "========================================\n\n";

// Update .env file
$envFile = "/Users/williamhunt/my-app/.env";
if (!file_exists($envFile)) {
    echo "❌ Error: .env file not found at $envFile\n";
    exit(1);
}

$envContent = file_get_contents($envFile);

// Update Medusa database configuration with correct credentials
$envContent = preg_replace('/MEDUSA_DB_USERNAME=.*/', 'MEDUSA_DB_USERNAME=my_app_user', $envContent);
$envContent = preg_replace('/MEDUSA_DB_PASSWORD=.*/', 'MEDUSA_DB_PASSWORD=my_secure_password', $envContent);
$envContent = preg_replace('/MEDUSA_DB_DATABASE=.*/', 'MEDUSA_DB_DATABASE=medusa-ethos-test', $envContent);

file_put_contents($envFile, $envContent);

echo "✅ Medusa database configuration updated with correct credentials\n";
echo "🔧 Database: medusa-ethos-test\n";
echo "🔧 Username: my_app_user\n";
echo "🔧 Password: my_secure_password\n\n";

echo "🎉 Next steps:\n";
echo "1. The sync will now create real products in Medusa database\n";
echo "2. Go to: http://localhost:8000/admin/stores\n";
echo "3. Click 'Sync All Stores' to test!\n";
echo "4. Check Medusa admin: http://localhost:9000/app/products\n\n";

echo "✅ Products will now appear in Medusa!\n";





