<?php

namespace App\Filament\Resources\DtfInHousePrintResource\Widgets;

use App\Models\DtfWidgetContent;
use Filament\Widgets\Widget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Support\Contracts\TranslatableContentDriver;

class ToneOnToneColors extends Widget implements HasActions
{
    use InteractsWithActions;
    protected static string $view = 'filament.resources.dtf-in-house-print-resource.widgets.tone-on-tone-colors';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 10;

    protected static bool $isLazy = false;

    public $colors = [];

    public function mount(): void
    {
        $widget = DtfWidgetContent::firstOrCreate(
            ['widget_name' => 'dtf_tone_on_tone_colors'],
            ['content' => '[]']
        );
        
        $this->colors = json_decode($widget->content ?: '[]', true) ?: [];
        
        // Cache actions on mount to ensure they're available
        foreach ($this->getActions() as $action) {
            if ($action instanceof Action) {
                $this->cacheAction($action);
            }
        }
    }

    public function editColors(): Action
    {
        return Action::make('edit_colors')
            ->label('Edit Colors')
            ->icon('heroicon-o-pencil')
            ->livewire($this)
            ->form([
                \Filament\Forms\Components\Repeater::make('colors')
                    ->label('Tone on Tone Colors')
                    ->schema([
                        TextInput::make('name')
                            ->label('Color Name')
                            ->required()
                            ->placeholder('e.g., Navy Blue'),
                        ColorPicker::make('darker')
                            ->label('Tone on Tone (Darker)')
                            ->required()
                            ->default('#000000'),
                        ColorPicker::make('lighter')
                            ->label('Tone on Tone (Lighter)')
                            ->required()
                            ->default('#f5f5f5'),
                    ])
                    ->defaultItems(count($this->colors) > 0 ? count($this->colors) : 1)
                    ->default($this->colors)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'New Color'),
            ])
            ->action(function (array $data): void {
                $widget = DtfWidgetContent::firstOrNew(['widget_name' => 'dtf_tone_on_tone_colors']);
                $widget->content = json_encode($data['colors'] ?? []);
                $widget->save();

                $this->colors = json_decode($widget->content, true) ?: [];

                Notification::make()
                    ->title('Tone on Tone colors updated successfully!')
                    ->success()
                    ->send();
            })
            ->requiresConfirmation(false)
            ->modalHeading('Edit Tone on Tone Colors')
            ->modalSubmitActionLabel('Save')
            ->modalWidth('4xl');
    }

    protected function getActions(): array
    {
        return [
            $this->editColors(),
        ];
    }

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        return null;
    }
}

