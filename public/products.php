<?php
header('Content-Type: application/json');

$response = [
    'status' => 'ok',
    'message' => 'Products endpoint',
    'timestamp' => date('Y-m-d H:i:s'),
    'laravel_status' => 'not_loaded',
    'products' => []
];

try {
    // Load Laravel
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    $response['laravel_status'] = 'loaded';
    
    // Try to get products from database
    try {
        $products = \App\Models\Product::all();
        $response['products'] = $products->toArray();
        $response['message'] = 'Products loaded successfully!';
    } catch (Exception $e) {
        $response['message'] = 'Products endpoint ready (database not connected)';
        $response['database_error'] = $e->getMessage();
    }
    
} catch (Exception $e) {
    $response['laravel_status'] = 'error';
    $response['error'] = $e->getMessage();
    $response['message'] = 'Laravel failed to load';
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
