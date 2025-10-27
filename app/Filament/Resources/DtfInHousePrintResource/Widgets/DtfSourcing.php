<?php

namespace App\Filament\Resources\DtfInHousePrintResource\Widgets;

use App\Models\DtfWidgetContent;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class DtfSourcing extends Widget implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string $view = 'filament.resources.dtf-in-house-print-resource.widgets.dtf-sourcing';

    protected int | string | array $columnSpan = 'half';

    protected static ?int $sort = 3;
    
    protected static bool $isLazy = false;

    public $content = '';

    public $showEditModal = false;
    public $editContent = '';

    public function mount(): void
    {
        $widget = DtfWidgetContent::firstOrCreate(
            ['widget_name' => 'dtf_sourcing'],
            ['content' => '']
        );
        
        $this->content = $widget->content ?: '';
    }
    
    public function openEditModal()
    {
        $this->editContent = $this->content;
        $this->showEditModal = true;
    }
    
    public function closeEditModal()
    {
        $this->showEditModal = false;
    }
    
    public function saveContent()
    {
        $widget = DtfWidgetContent::firstOrNew(['widget_name' => 'dtf_sourcing']);
        $widget->content = $this->editContent;
        $widget->save();
        
        $this->content = $widget->content;
        $this->showEditModal = false;
        
        Notification::make()
            ->title('Content updated successfully!')
            ->success()
            ->send();
    }
}

