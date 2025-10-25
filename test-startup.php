<?php

// Simple test to see if the app can start
echo "Testing Laravel startup...\n";

// Check if we can load the app
try {
    require_once __DIR__ . '/vendor/autoload.php';
    echo "✓ Autoloader loaded\n";
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✓ App bootstrap loaded\n";
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✓ HTTP Kernel loaded\n";
    
    echo "✓ Laravel app can start successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
