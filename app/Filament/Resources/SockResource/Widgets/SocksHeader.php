<?php

namespace App\Filament\Resources\SockResource\Widgets;

use App\Models\TeamNote;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SocksHeader extends Widget
{
    protected static string $view = 'filament.resources.sock-resource.widgets.socks-header';

    protected int | string | array $columnSpan = 'full';

    public $content = '';
    public $isEditable = false;

    public function mount(): void
    {
        // Check if user is super admin - check for 'super-admin' role
        $user = Auth::user();
        
        // TEMPORARY: Allow all logged-in users to edit for testing
        $this->isEditable = $user !== null;
        
        // Get or create the team note for this page
        $teamNote = TeamNote::firstOrCreate(
            ['page' => 'socks'],
            ['content' => '']
        );
        
        // Get content, use empty string if null or invalid
        $content = $teamNote->content ?? '';
        
        // Simple UTF-8 sanitization
        if ($content && !mb_check_encoding($content, 'UTF-8')) {
            $content = '';
        }
        
        $this->content = $content ?: '';
    }

    public function getViewData(): array
    {
        return [];
    }

    public function editNotes(): Action
    {
        return Action::make('edit_notes')
            ->label('Edit Notes')
            ->icon('heroicon-o-pencil-square')
            ->color('gray')
            ->form([
                Textarea::make('content')
                    ->label('Team Notes')
                    ->placeholder('Enter notes for your team...')
                    ->rows(5)
                    ->default(fn () => $this->content),
            ])
            ->action(function (array $data): void {
                $teamNote = TeamNote::firstOrNew(['page' => 'socks']);
                $teamNote->content = $data['content'];
                $teamNote->save();

                Notification::make()
                    ->title('Notes updated successfully!')
                    ->success()
                    ->send();
                
                // Refresh the content
                $this->content = $teamNote->content;
            })
            ->requiresConfirmation(false)
            ->modalHeading('Edit Team Notes')
            ->modalSubmitActionLabel('Save');
    }

    protected function getActions(): array
    {
        if (!$this->isEditable) {
            return [];
        }

        return [
            $this->editNotes(),
        ];
    }
}

