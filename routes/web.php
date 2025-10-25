<?php

use App\Http\Controllers\PurchaseOrderController;
use App\Models\FileManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Purchase Order PDF Routes
Route::get('/purchase-orders/{purchaseOrder}/pdf', [PurchaseOrderController::class, 'generatePDF'])->name('purchase-orders.pdf');
Route::get('/purchase-orders/{purchaseOrder}/view', [PurchaseOrderController::class, 'viewPDF'])->name('purchase-orders.view');

// File Preview Route
Route::get('/file-preview/{fileManager}', function (FileManager $fileManager) {
    return view('file-preview', compact('fileManager'));
})->name('file-preview');