<?php

namespace App\Filament\Resources\DtfInHousePrintResource\Widgets;

use App\Models\DtfWidgetContent;
use Filament\Widgets\Widget;

class ContactInfo extends Widget
{
    protected static string $view = 'filament.resources.dtf-in-house-print-resource.widgets.contact-info';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 6;
    
    protected static bool $isLazy = false;

    public $content = '';
    public $showForm = false;

    public function mount(): void
    {
        $widget = DtfWidgetContent::firstOrCreate(
            ['widget_name' => 'dtf_contact_info'],
            ['content' => '']
        );
        
        $this->content = $widget->content ?: '';
    }

    public function toggleEdit(): void
    {
        $this->showForm = !$this->showForm;
    }

    public function saveContent(): void
    {
        $widget = DtfWidgetContent::firstOrNew(['widget_name' => 'dtf_contact_info']);
        $widget->content = $this->content;
        $widget->save();

        $this->showForm = false;

        \Filament\Notifications\Notification::make()
            ->title('Content updated successfully!')
            ->success()
            ->send();
    }
}

