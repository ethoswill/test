<?php
header('Content-Type: application/json');

$response = [
    'status' => 'ok',
    'message' => 'Admin Dashboard',
    'timestamp' => date('Y-m-d H:i:s'),
    'laravel_status' => 'not_loaded',
    'dashboard' => []
];

try {
    // Load Laravel
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    $response['laravel_status'] = 'loaded';
    $response['framework'] = 'Laravel ' . $app->version();
    
    // Try to get dashboard data
    try {
        // Check if database is configured
        $dbConfig = $app['config']['database.default'];
        if (!$dbConfig || $dbConfig === 'sqlite') {
            throw new Exception('Database not configured for production');
        }
        
        $dashboard = [
            'products_count' => \App\Models\Product::count(),
            'stores_count' => \App\Models\Store::count(),
            'files_count' => \App\Models\FileManager::count(),
            'database_connected' => true
        ];
        $response['dashboard'] = $dashboard;
        $response['message'] = 'Dashboard loaded with database data!';
    } catch (Exception $e) {
        $dashboard = [
            'products_count' => 'N/A (database not connected)',
            'stores_count' => 'N/A (database not connected)',
            'files_count' => 'N/A (database not connected)',
            'database_connected' => false,
            'error' => $e->getMessage()
        ];
        $response['dashboard'] = $dashboard;
        $response['message'] = 'Dashboard loaded (database not connected)';
    }
    
    // Add available endpoints
    $response['endpoints'] = [
        'main' => '/',
        'admin' => '/admin.php',
        'products' => '/products.php',
        'stores' => '/stores.php',
        'files' => '/files.php',
        'dashboard' => '/dashboard.php',
        'health' => '/health.php'
    ];
    
} catch (Exception $e) {
    $response['laravel_status'] = 'error';
    $response['error'] = $e->getMessage();
    $response['message'] = 'Laravel failed to load';
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
