<?php

use Illuminate\Support\Facades\Route;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

Route::get('/', function () {
    return view('welcome');
});

// Secure CAD file download route
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/admin/media/{media}/download', function (Media $media) {
        // Check if the media belongs to a product and user has access
        $model = $media->model;
        if ($model && method_exists($model, 'getMedia')) {
            // Verify the media collection is 'cad_download'
            if ($media->collection_name === 'cad_download') {
                return $media->getDisk()->download(
                    $media->getPath(),
                    $media->file_name
                );
            }
        }
        
        abort(404);
    })->name('filament.admin.media.download');
});
