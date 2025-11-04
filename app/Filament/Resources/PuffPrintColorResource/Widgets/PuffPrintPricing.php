<?php

namespace App\Filament\Resources\PuffPrintColorResource\Widgets;

use App\Models\TeamNote;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class PuffPrintPricing extends Widget
{
    protected static string $view = 'filament.resources.puff-print-color-resource.widgets.puff-print-pricing';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public $content = '';
    public $isEditable = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->isEditable = $user !== null;
        
        $teamNote = TeamNote::firstOrCreate(
            ['page' => 'puff-print-pricing'],
            ['content' => '']
        );
        
        $content = $teamNote->content ?? '';
        if ($content && !mb_check_encoding($content, 'UTF-8')) {
            $content = '';
        }
        
        $this->content = $content ?: '';
    }

    public function getViewData(): array
    {
        return [];
    }

    public function editContent(): Action
    {
        return Action::make('edit_content')
            ->label('Edit Pricing')
            ->icon('heroicon-o-pencil-square')
            ->color('gray')
            ->form([
                Textarea::make('content')
                    ->label('Pricing')
                    ->placeholder('Enter pricing information...')
                    ->rows(5)
                    ->default(fn () => $this->content),
            ])
            ->action(function (array $data): void {
                $teamNote = TeamNote::firstOrNew(['page' => 'puff-print-pricing']);
                $teamNote->content = $data['content'];
                $teamNote->save();

                Notification::make()
                    ->title('Pricing updated successfully!')
                    ->success()
                    ->send();
                
                $this->content = $teamNote->content;
            })
            ->requiresConfirmation(false)
            ->modalHeading('Edit Pricing')
            ->modalSubmitActionLabel('Save');
    }

    protected function getActions(): array
    {
        if (!$this->isEditable) {
            return [];
        }

        return [
            $this->editContent(),
        ];
    }
}

