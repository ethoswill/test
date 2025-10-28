<?php

namespace App\Filament\Resources\SockCustomizationMethodResource\Widgets;

use App\Models\SockCustomizationMethodContent;
use Filament\Widgets\Widget;

class CustomizationMethods extends Widget
{
    protected static string $view = 'filament.resources.sock-customization-method-resource.widgets.customization-methods';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;
    
    protected static bool $isLazy = false;

    public $methods = [];
    public $showForm = false;
    public $currentImageIndices = []; // To track current carousel image index for each method
    public $methodNames = []; // To track the display names of each method

    public function mount(): void
    {
        $defaultMethodKeys = ['method_1', 'method_2', 'method_3', 'method_4', 'method_5'];
        $defaultMethodNames = ['Method 1', 'Method 2', 'Method 3', 'Method 4', 'Method 5'];
        
        foreach ($defaultMethodKeys as $index => $key) {
            $defaultName = $defaultMethodNames[$index];
            
            $content = SockCustomizationMethodContent::firstOrCreate(
                ['method_name' => $key],
                [
                    'notes' => json_encode(['display_name' => $defaultName, 'notes' => '']),
                    'image_urls' => []
                ]
            );
            
            // Parse the display name from notes if it exists
            $data = json_decode($content->notes, true);
            $displayName = $data['display_name'] ?? $defaultName;
            
            $this->methods[$key] = [
                'id' => $content->id,
                'display_name' => $displayName,
                'notes' => $data['notes'] ?? '',
                'image_urls' => $content->image_urls ?: [],
                'new_image_url' => '',
            ];
            
            // Store the mapping of key to display name
            $this->methodNames[$key] = $displayName;
            
            // Initialize current image index to 0 for each method
            $this->currentImageIndices[$key] = 0;
        }
    }

    public function toggleEdit(): void
    {
        $this->showForm = !$this->showForm;
    }

    public function saveMethod($methodName): void
    {
        $method = $this->methods[$methodName];
        
        $content = SockCustomizationMethodContent::find($method['id']);
        if ($content) {
            // Store notes and display_name as JSON in the notes field
            $content->notes = json_encode([
                'display_name' => $method['display_name'],
                'notes' => $method['notes']
            ]);
            $content->image_urls = $method['image_urls'];
            $content->save();
        }

        \Filament\Notifications\Notification::make()
            ->title('Method updated successfully!')
            ->success()
            ->send();
    }

    public function addImage($methodName): void
    {
        if (!empty($this->methods[$methodName]['new_image_url'])) {
            $url = $this->methods[$methodName]['new_image_url'];
            
            // Validate URL
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                \Filament\Notifications\Notification::make()
                    ->title('Invalid URL')
                    ->danger()
                    ->send();
                return;
            }

            // Check if it's an image URL
            $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            
            if (!in_array($extension, $allowedExtensions)) {
                \Filament\Notifications\Notification::make()
                    ->title('Please use an image URL (jpg, png, gif, webp, svg)')
                    ->danger()
                    ->send();
                return;
            }

            $this->methods[$methodName]['image_urls'][] = $url;
            $this->methods[$methodName]['new_image_url'] = '';
            
            // Set the newly added image as the current carousel image
            $this->currentImageIndices[$methodName] = count($this->methods[$methodName]['image_urls']) - 1;
            
            \Filament\Notifications\Notification::make()
                ->title('Image added! Click Save to keep changes.')
                ->success()
                ->send();
        }
    }

    public function removeImage($methodName, $index): void
    {
        if (isset($this->methods[$methodName]['image_urls'][$index])) {
            array_splice($this->methods[$methodName]['image_urls'], $index, 1);
            
            // Adjust current index if necessary
            if ($this->currentImageIndices[$methodName] >= count($this->methods[$methodName]['image_urls'])) {
                $this->currentImageIndices[$methodName] = max(0, count($this->methods[$methodName]['image_urls']) - 1);
            }
        }
    }

    public function nextImage($methodName): void
    {
        $totalImages = count($this->methods[$methodName]['image_urls']);
        if ($totalImages > 0) {
            $this->currentImageIndices[$methodName] = ($this->currentImageIndices[$methodName] + 1) % $totalImages;
        }
    }

    public function previousImage($methodName): void
    {
        $totalImages = count($this->methods[$methodName]['image_urls']);
        if ($totalImages > 0) {
            $this->currentImageIndices[$methodName] = ($this->currentImageIndices[$methodName] - 1 + $totalImages) % $totalImages;
        }
    }
}

