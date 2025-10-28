<?php

namespace App\Filament\Resources\DtfInHousePrintResource\Widgets;

use App\Models\DtfWidgetContent;
use Filament\Widgets\Widget;

class IccProfiling extends Widget
{
    protected static string $view = 'filament.resources.dtf-in-house-print-resource.widgets.icc-profiling';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 7;
    
    protected static bool $isLazy = false;

    public $content = '';
    public $existingImages = [];
    public $showForm = false;
    public $imageUrl = '';

    public function mount(): void
    {
        $widget = DtfWidgetContent::firstOrCreate(
            ['widget_name' => 'icc_profiling'],
            ['content' => '']
        );
        
        // Load data from JSON in content field
        $data = json_decode($widget->content ?: '{}', true) ?: [];
        $this->content = $data['text'] ?? '';
        $this->existingImages = $data['images'] ?? [];
    }

    public function toggleEdit(): void
    {
        $this->showForm = !$this->showForm;
    }

    public function removeImage($index): void
    {
        if (isset($this->existingImages[$index])) {
            $imagePath = $this->existingImages[$index];
            // Remove from array
            unset($this->existingImages[$index]);
            $this->existingImages = array_values($this->existingImages);
            
            // Save to database
            $widget = DtfWidgetContent::firstOrNew(['widget_name' => 'icc_profiling']);
            $widget->content = json_encode([
                'text' => $this->content,
                'images' => $this->existingImages
            ]);
            $widget->save();

            \Filament\Notifications\Notification::make()
                ->title('Image removed successfully!')
                ->success()
                ->send();

            $this->dispatch('image-removed');
        }
    }

    public function saveContent(): void
    {
        $widget = DtfWidgetContent::firstOrNew(['widget_name' => 'icc_profiling']);
        $widget->content = json_encode([
            'text' => $this->content,
            'images' => $this->existingImages
        ]);
        $widget->save();

        $this->showForm = false;

        \Filament\Notifications\Notification::make()
            ->title('Content updated successfully!')
            ->success()
            ->send();
    }

    public function addImageFromUrl(): void
    {
        if (empty($this->imageUrl)) {
            return;
        }

        // Validate URL
        if (!filter_var($this->imageUrl, FILTER_VALIDATE_URL)) {
            \Filament\Notifications\Notification::make()
                ->title('Invalid URL')
                ->danger()
                ->send();
            return;
        }

        // Check if it's an image URL
        $extension = strtolower(pathinfo(parse_url($this->imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        
        if (!in_array($extension, $allowedExtensions)) {
            \Filament\Notifications\Notification::make()
                ->title('Please use an image URL (jpg, png, gif, webp, svg)')
                ->danger()
                ->send();
            return;
        }

        $this->existingImages[] = $this->imageUrl;
        $this->imageUrl = '';
        
        // Auto-save to database
        $widget = DtfWidgetContent::firstOrNew(['widget_name' => 'icc_profiling']);
        $widget->content = json_encode([
            'text' => $this->content,
            'images' => $this->existingImages
        ]);
        $widget->save();

        \Filament\Notifications\Notification::make()
            ->title('Image added successfully!')
            ->success()
            ->send();
    }
}

