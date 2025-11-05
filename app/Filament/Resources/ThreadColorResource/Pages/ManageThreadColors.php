<?php

namespace App\Filament\Resources\ThreadColorResource\Pages;

use App\Filament\Resources\ThreadColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\ThreadColor;
use Filament\Forms;

class ManageThreadColors extends ManageRecords
{
    protected static string $resource = ThreadColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
            Action::make('download_swatches')
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
                }),
        ];
    }
}