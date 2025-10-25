#!/bin/bash

echo "=== LARAVEL TEST STAGE 1: Test Laravel Loading ==="

# Create a test index.php that tries to load Laravel but falls back gracefully
cat > public/index.php << 'EOF'
<?php
header('Content-Type: application/json');

$response = [
    'status' => 'ok',
    'message' => 'Testing Laravel integration',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'laravel_test' => []
];

try {
    // Test 1: Check if vendor/autoload.php exists
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        $response['laravel_test']['autoload'] = 'exists';
        
        // Test 2: Try to load autoloader
        require_once __DIR__ . '/../vendor/autoload.php';
        $response['laravel_test']['autoload_loaded'] = 'success';
        
        // Test 3: Check if bootstrap/app.php exists
        if (file_exists(__DIR__ . '/../bootstrap/app.php')) {
            $response['laravel_test']['bootstrap'] = 'exists';
            
            // Test 4: Try to load Laravel app (but don't start it)
            $app = require_once __DIR__ . '/../bootstrap/app.php';
            $response['laravel_test']['app_loaded'] = 'success';
            $response['laravel_test']['laravel_version'] = $app->version();
            $response['message'] = 'Laravel loaded successfully!';
        } else {
            $response['laravel_test']['bootstrap'] = 'missing';
        }
    } else {
        $response['laravel_test']['autoload'] = 'missing';
    }
} catch (Exception $e) {
    $response['laravel_test']['error'] = $e->getMessage();
    $response['message'] = 'Laravel failed to load, using fallback';
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
EOF

echo "Starting Laravel test server..."
exec php -S 0.0.0.0:$PORT -t public
