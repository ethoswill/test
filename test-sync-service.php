<?php

/**
 * Test the MedusaSyncService directly
 */

// Bootstrap Laravel
require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Load environment variables
$apiKey = config('medusa.api_key');

echo "🧪 Testing MedusaSyncService\n";
echo "============================\n\n";

if (empty($apiKey) || $apiKey === 'your-api-key-here') {
    echo "❌ Error: MEDUSA_API_KEY not set in .env file\n";
    exit(1);
}

echo "✅ API Key found: " . substr($apiKey, 0, 10) . "...\n\n";

// Test the sync service
try {
    $syncService = new \App\Services\MedusaSyncService();
    
    // Create a test product
    $testProduct = new \App\Models\Product();
    $testProduct->id = 999;
    $testProduct->name = 'Test Product from Laravel';
    $testProduct->supplier_code = 'TEST-001';
    $testProduct->status = 'Active';
    $testProduct->ethos_cost_price = 10.00;
    $testProduct->customer_b2b_price = 15.00;
    $testProduct->customer_dtc_price = 20.00;
    $testProduct->franchisee_price = 12.00;
    
    echo "🔄 Testing sync with test product...\n";
    $result = $syncService->syncProduct($testProduct);
    
    if ($result['success']) {
        echo "✅ Sync successful!\n";
        echo "📝 Message: " . $result['message'] . "\n";
        echo "🆔 Medusa ID: " . ($result['medusa_id'] ?? 'N/A') . "\n";
        echo "🆔 Laravel ID: " . $result['laravel_id'] . "\n";
        
        if (isset($result['action'])) {
            echo "🎯 Action: " . $result['action'] . "\n";
        }
        
        echo "\n🎉 Sync is working perfectly!\n";
        echo "You can now use the sync functionality in the stores page.\n";
        
    } else {
        echo "❌ Sync failed: " . $result['message'] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error testing sync service: " . $e->getMessage() . "\n";
}

echo "\n📚 Next steps:\n";
echo "1. Go to: http://localhost:8000/admin/stores\n";
echo "2. Click 'Sync All Stores' to test the full sync\n";
echo "3. Watch the sync notifications appear!\n";
