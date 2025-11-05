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
        $galleryItems = [];
        
        if ($this->record instanceof Sock) {
            // Get gallery_images from the record
            $galleryData = $this->record->gallery_images;
            
            if (is_array($galleryData)) {
                foreach ($galleryData as $item) {
                    if (is_array($item) && !empty($item['url'])) {
                        $galleryItems[] = [
                            'url' => trim($item['url']),
                            'description' => trim($item['description'] ?? '')
                        ];
                    } elseif (is_string($item)) {
                        // Legacy format: just URL string
                        $galleryItems[] = [
                            'url' => trim($item),
                            'description' => ''
                        ];
                    }
                }
            }
        }
        
        return [
            'galleryItems' => $galleryItems,
        ];
    }
}

