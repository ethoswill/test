<?php

namespace App\Filament\Resources\DtfInHousePrintResource\Widgets;

use App\Models\DtfWidgetContent;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class LeadTimesMinimums extends Widget
{
    protected static string $view = 'filament.resources.dtf-in-house-print-resource.widgets.lead-times-minimums';

    protected int | string | array $columnSpan = 'half';

    protected static ?int $sort = 6;
    
    protected static bool $isLazy = false;

    public $content = '';

    public function mount(): void
    {
        $widget = DtfWidgetContent::firstOrCreate(
            ['widget_name' => 'lead_times_minimums'],
            ['content' => '']
        );
        
        $this->content = $widget->content ?: '';
    }

    public function editContent(): Action
    {
        return Action::make('edit_content')
            ->label('Edit')
            ->icon('heroicon-o-pencil')
            ->form([
                Textarea::make('content')
                    ->label('Content')
                    ->rows(10)
                    ->default(fn () => $this->content),
            ])
            ->action(function (array $data): void {
                $widget = DtfWidgetContent::firstOrNew(['widget_name' => 'lead_times_minimums']);
                $widget->content = $data['content'];
                $widget->save();

                $this->content = $widget->content;

                Notification::make()
                    ->title('Content updated successfully!')
                    ->success()
                    ->send();
            })
            ->requiresConfirmation(false)
            ->modalHeading('Edit Lead Times & Minimums')
            ->modalSubmitActionLabel('Save');
    }

    protected function getActions(): array
    {
        return [
            $this->editContent(),
        ];
    }
}

