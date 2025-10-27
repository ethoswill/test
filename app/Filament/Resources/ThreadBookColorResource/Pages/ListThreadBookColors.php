<?php

namespace App\Filament\Resources\ThreadBookColorResource\Pages;

use App\Filament\Resources\ThreadBookColorResource;
use App\Filament\Resources\ThreadBookColorResource\Widgets\ThreadBookColorsHeader;
use App\Models\TeamNote;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class ListThreadBookColors extends ListRecords
{
    protected static string $resource = ThreadBookColorResource::class;

    public function getHeaderWidgets(): array
    {
        return [
            ThreadBookColorsHeader::class,
        ];
    }

    public function getHeaderActions(): array
    {
        $actions = [];

        // Add download button
        $actions[] = Action::make('download_all_cads')
            ->label('Download Thread Book CADs')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('info')
            ->url('https://cdn.shopify.com/s/files/1/0609/4752/9901/files/Grip_Pattern_Downloads.pdf?v=1761505527', shouldOpenInNewTab: true);

        // Add team notes edit action
        $teamNote = TeamNote::firstOrCreate(['page' => 'thread-book-colors'], ['content' => '']);
        
        $actions[] = Action::make('edit_team_notes')
            ->label('Edit Team Notes')
            ->icon('heroicon-o-pencil-square')
            ->color('gray')
            ->form([
                Textarea::make('content')
                    ->label('Team Notes')
                    ->placeholder('First line will be a bold header, rest as bullets:' . PHP_EOL . 'Header Title' . PHP_EOL . 'Note 1' . PHP_EOL . 'Note 2')
                    ->rows(5)
                    ->helperText('First line becomes a bold header. Each additional line is a bullet point.')
                    ->default(mb_convert_encoding($teamNote->content ?: '', 'UTF-8', 'UTF-8')),
            ])
            ->action(function (array $data): void {
                // Clean and ensure UTF-8 encoding
                $content = $data['content'] ?? '';
                
                // Strip invalid UTF-8 characters
                $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
                $content = iconv('UTF-8', 'UTF-8//IGNORE', $content);
                
                $teamNote = TeamNote::firstOrNew(['page' => 'thread-book-colors']);
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
