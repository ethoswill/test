<?php

namespace App\Filament\Resources\SockResource\Widgets;

use App\Models\Sock;
use Filament\Widgets\Widget;

class SockGallery extends Widget
{
    protected static string $view = 'filament.resources.sock-resource.widgets.sock-gallery';
    
    protected int | string | array $columnSpan = 'full';
    
    protected function getViewData(): array
    {
        $record = null;
        
        // Get the record from the parent ViewRecord page
        if ($this->getParent() && method_exists($this->getParent(), 'getRecord')) {
            $record = $this->getParent()->getRecord();
        }
        
        $imageUrls = [];
        
        if ($record instanceof Sock) {
            // Get gallery_images from the record
            if (is_array($record->gallery_images)) {
                $imageUrls = array_filter($record->gallery_images);
            } elseif (is_string($record->gallery_images)) {
                $decoded = json_decode($record->gallery_images, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $imageUrls = array_filter($decoded);
                } else {
                    $imageUrls = array_filter(array_map('trim', explode("\n", $record->gallery_images)));
                }
            }
        }
        
        return [
            'imageUrls' => $imageUrls,
        ];
    }
}

