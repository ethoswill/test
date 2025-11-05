<?php

namespace App\Filament\Resources\SockResource\Widgets;

use App\Models\Sock;
use Filament\Widgets\Widget;
use Livewire\Attributes\Reactive;

class SockGallery extends Widget
{
    protected static string $view = 'filament.resources.sock-resource.widgets.sock-gallery';
    
    protected int | string | array $columnSpan = 'full';
    
    #[Reactive]
    public ?Sock $record = null;
    
    protected function getViewData(): array
    {
        $imageUrls = [];
        
        if ($this->record instanceof Sock) {
            // Get gallery_images from the record
            if (is_array($this->record->gallery_images)) {
                $imageUrls = array_filter($this->record->gallery_images);
            } elseif (is_string($this->record->gallery_images)) {
                $decoded = json_decode($this->record->gallery_images, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $imageUrls = array_filter($decoded);
                } else {
                    $imageUrls = array_filter(array_map('trim', explode("\n", $this->record->gallery_images)));
                }
            }
        }
        
        return [
            'imageUrls' => $imageUrls,
        ];
    }
}

