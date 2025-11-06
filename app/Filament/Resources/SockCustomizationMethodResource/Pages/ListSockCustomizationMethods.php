<?php

namespace App\Filament\Resources\SockCustomizationMethodResource\Pages;

use App\Filament\Resources\SockCustomizationMethodResource;
use App\Filament\Resources\SockCustomizationMethodResource\Widgets\CustomizationMethods;
use App\Filament\Resources\SockCustomizationMethodResource\Widgets\SockCustomizationMethodsHeader;
use App\Models\TeamNote;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;

class ListSockCustomizationMethods extends ListRecords
{
    protected static string $resource = SockCustomizationMethodResource::class;

    public function getHeaderWidgets(): array
    {
        return [
            SockCustomizationMethodsHeader::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make(),
        ];

        // Add team notes edit action
        $teamNote = TeamNote::firstOrCreate(['page' => 'sock-customization-methods'], ['content' => '']);
        
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
                
                $teamNote = TeamNote::firstOrNew(['page' => 'sock-customization-methods']);
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
