<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardHeader;
use App\Models\TeamNote;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class CustomDashboard extends BaseDashboard
{
    public function getTitle(): string | Htmlable
    {
        $user = auth()->user();
        $firstName = $user->first_name ?? $user->name;
        return 'Hello ' . $firstName;
    }

    public function getVisibleWidgets(): array
    {
        // Get all visible widgets but exclude DashboardHeader since it's in header widgets
        $widgets = parent::getVisibleWidgets();
        return array_filter($widgets, function($widget) {
            if (is_string($widget)) {
                return $widget !== DashboardHeader::class;
            }
            // Handle WidgetConfiguration objects
            if (is_object($widget) && method_exists($widget, 'getWidget')) {
                return $widget->getWidget() !== DashboardHeader::class;
            }
            return true;
        });
    }

    public function getHeaderWidgets(): array
    {
        return [
            DashboardHeader::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        // Add team notes edit action
        $teamNote = TeamNote::firstOrCreate(['page' => 'dashboard'], ['content' => '']);
        
        $actions[] = Action::make('edit_team_notes')
            ->label('Edit Team Notes')
            ->icon('heroicon-o-pencil-square')
            ->color('gray')
            ->form([
                RichEditor::make('content')
                    ->label('Team Notes')
                    ->placeholder('Enter your notes here. You can use HTML tags like <h3>Heading</h3> and <br> for line breaks.')
                    ->helperText('You can use HTML tags like <h3>, <h2>, <br>, <p>, <strong>, <em>, etc.')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->default(mb_convert_encoding($teamNote->content ?: '', 'UTF-8', 'UTF-8')),
            ])
            ->action(function (array $data): void {
                // Clean and ensure UTF-8 encoding
                $content = $data['content'] ?? '';
                
                // Strip invalid UTF-8 characters
                $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
                $content = iconv('UTF-8', 'UTF-8//IGNORE', $content);
                
                $teamNote = TeamNote::firstOrNew(['page' => 'dashboard']);
                $teamNote->content = $content;
                $teamNote->save();

                Notification::make()
                    ->title('Notes updated successfully!')
                    ->success()
                    ->send();
            })
            ->requiresConfirmation(false)
            ->modalHeading('Edit Team Notes')
            ->modalSubmitActionLabel('Save');

        return $actions;
    }
}
