<?php

namespace App\Filament\Resources\ThreadColorResource\Pages;

use App\Filament\Resources\ThreadColorResource;
use App\Filament\Resources\ThreadColorResource\Widgets\ThreadColorsHeader;
use App\Models\TeamNote;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\ThreadColor;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;

class ManageThreadColors extends ManageRecords
{
    protected static string $resource = ThreadColorResource::class;

    public function getHeaderWidgets(): array
    {
        return [
            ThreadColorsHeader::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make(),
            Action::make('add_rows')
                ->label('Add Rows')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->form([
                    Forms\Components\Textarea::make('rows')
                        ->label('Paste Thread Numbers')
                        ->placeholder('Paste one thread number per line (e.g., 211, 672, 1001)')
                        ->helperText('Each line will create a new thread color. Only thread numbers are required. Optional: Add hex code using pipe format (211|#FFFFFF)')
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
                        
                        // Parse the line - support pipe-separated format: color_name|hex_code
                        $parts = explode('|', $line);
                        $colorName = trim($parts[0]);
                        $hexCode = isset($parts[1]) ? trim($parts[1]) : null;
                        
                        if (empty($colorName)) {
                            $skipped++;
                            continue;
                        }
                        
                        // Check if thread color already exists
                        $existing = ThreadColor::where('color_name', $colorName)->first();
                        if ($existing) {
                            $skipped++;
                            continue;
                        }
                        
                        // Create new thread color
                        // Use color_name as fallback for color_code if hex_code is not provided
                        ThreadColor::create([
                            'color_name' => $colorName,
                            'color_code' => $hexCode ?: $colorName,
                            'hex_code' => $hexCode ?: null,
                        ]);
                        
                        $created++;
                    }
                    
                    Notification::make()
                        ->title('Thread colors added successfully!')
                        ->body("Created {$created} new thread color(s). " . ($skipped > 0 ? "{$skipped} skipped (already exist or invalid)." : ''))
                        ->success()
                        ->send();
                })
                ->modalHeading('Add Thread Colors from Text')
                ->modalSubmitActionLabel('Add Rows'),
        ];
        
        $actions[] = Action::make('download_swatches')
            ->label('Download Thread Color Swatches')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('info')
            ->action(function (): void {
                // TODO: Map file download functionality
                Notification::make()
                    ->title('Download functionality coming soon')
                    ->body('The download feature will be implemented soon.')
                    ->info()
                    ->send();
            });

        // Add team notes edit action
        $teamNote = TeamNote::firstOrCreate(['page' => 'thread-colors'], ['content' => '']);
        
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
                
                $teamNote = TeamNote::firstOrNew(['page' => 'thread-colors']);
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