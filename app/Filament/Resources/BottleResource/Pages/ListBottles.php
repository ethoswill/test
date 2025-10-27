<?php

namespace App\Filament\Resources\BottleResource\Pages;

use App\Filament\Resources\BottleResource;
use App\Filament\Resources\BottleResource\Widgets\BottlesHeader;
use App\Models\TeamNote;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListBottles extends ListRecords
{
    protected static string $resource = BottleResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make()
                ->color('success'),
            Actions\Action::make('download_bottle_cads')
                ->label('Download Bottle CADs')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->url('https://cdn.shopify.com/s/files/1/0609/4752/9901/files/Bottle_CADs.pdf?v=1761535475', shouldOpenInNewTab: true),
        ];

        // Add team notes edit action
        $teamNote = TeamNote::firstOrCreate(['page' => 'bottles'], ['content' => '']);
        
        $actions[] = Actions\Action::make('edit_team_notes')
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
                
                $teamNote = TeamNote::firstOrNew(['page' => 'bottles']);
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

    protected function getHeaderWidgets(): array
    {
        return [
            BottlesHeader::class,
        ];
    }
}
