<?php

use App\Http\Controllers\PurchaseOrderController;
use App\Models\FileManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Laravel app is running',
        'admin_url' => url('/admin'),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
});

// Health check endpoint for Railway
Route::get('/health', function () {
    try {
        // Simple health check that doesn't require database
        return response()->json([
            'status' => 'ok',
            'timestamp' => date('Y-m-d H:i:s'),
            'app' => 'running'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Purchase Order PDF Routes
Route::get('/purchase-orders/{purchaseOrder}/pdf', [PurchaseOrderController::class, 'generatePDF'])->name('purchase-orders.pdf');
Route::get('/purchase-orders/{purchaseOrder}/view', [PurchaseOrderController::class, 'viewPDF'])->name('purchase-orders.view');

// File Preview Route
Route::get('/file-preview/{fileManager}', function (FileManager $fileManager) {
    return view('file-preview', compact('fileManager'));
})->name('file-preview');