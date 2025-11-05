<?php

namespace App\Filament\Resources\ThreadBookColorResource\Pages;

use App\Filament\Resources\ThreadBookColorResource;
use App\Filament\Resources\ThreadBookColorResource\Widgets\ThreadBookColorsHeader;
use App\Models\TeamNote;
use App\Models\ThreadBookColor;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Forms;

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
        $actions = [
            Action::make('create_thread_book_color')
                ->label('New Thread Book Color')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->url(ThreadBookColorResource::getUrl('create')),
            Action::make('add_rows')
                ->label('Add Rows')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->form([
                    Forms\Components\Textarea::make('rows')
                        ->label('Paste Thread Book Colors')
                        ->placeholder('Paste one thread book color per line (e.g., Navy Blue or Navy Blue|#000080)')
                        ->helperText('Each line will create a new thread book color. Only color name is required. Optional: Add hex code using pipe format (Color Name|#HEXCODE)')
                        ->rows(10)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $rows = $data['rows'];
                    $lines = array_filter(array_map('trim', explode("\n", $rows)));
                    
                    $created = 0;
                    $skipped = 0;
                    
                    foreach ($lines as $line) {
                        if (empty($line)) {
                            continue;
                        }
                        
                        // Parse the line - support pipe-separated format: name|hex_code
                        $parts = explode('|', $line);
                        $colorName = trim($parts[0]);
                        $hexCode = isset($parts[1]) ? trim($parts[1]) : null;
                        
                        if (empty($colorName)) {
                            $skipped++;
                            continue;
                        }
                        
                        // Check if thread book color already exists
                        $existing = ThreadBookColor::where('name', $colorName)->first();
                        if ($existing) {
                            $skipped++;
                            continue;
                        }
                        
                        // Create new thread book color
                        ThreadBookColor::create([
                            'name' => $colorName,
                            'color_code' => $hexCode ?: null,
                            'hex_code' => $hexCode ?: null,
                        ]);
                        
                        $created++;
                    }
                    
                    Notification::make()
                        ->title('Thread book colors added successfully!')
                        ->body("Created {$created} new thread book color(s). " . ($skipped > 0 ? "{$skipped} skipped (already exist or invalid)." : ''))
                        ->success()
                        ->send();
                })
                ->modalHeading('Add Thread Book Colors from Text')
                ->modalSubmitActionLabel('Add Rows'),
        ];

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
