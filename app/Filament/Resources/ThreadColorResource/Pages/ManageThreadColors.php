<?php

namespace App\Filament\Resources\ThreadColorResource\Pages;

use App\Filament\Resources\ThreadColorResource;
use App\Filament\Resources\ThreadColorResource\Imports\ThreadColorImporter;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Services\GoogleSheetsService;
use App\Services\GoogleDriveService;
use App\Models\ThreadColor;
use Filament\Forms;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\HtmlString;

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
            Actions\ImportAction::make()
                ->importer(ThreadColorImporter::class)
                ->label('Import Thread Colors')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->slideOver()
                ->mutateFormDataUsing(function (array $data): array {
                    // Add helper text about CSV template
                    return $data;
                }),
            Action::make('download_csv_template')
                ->label('Download CSV Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->action(fn () => $this->downloadCsvTemplate()),
        ];
    }

    public function downloadCsvTemplate()
    {
        $headers = [
            'color_name',
            'color_code',
            'image_url',
            'used_in',
        ];
        
        // Create CSV content with headers
        $csvContent = implode(',', $headers) . "\n";
        
        // Add sample rows with example data
        $sampleRows = [
            [
                '1001',
                'White',
                'https://cdn.shopify.com/s/files/1/0609/4752/9901/files/thread-white-1001.png',
                'White T-Shirts, Cream Products',
            ],
            [
                '1002',
                'Black',
                'https://cdn.shopify.com/s/files/1/0609/4752/9901/files/thread-black-1002.png',
                'Black T-Shirts, Navy Products',
            ],
            [
                '1003',
                'Red',
                'https://cdn.shopify.com/s/files/1/0609/4752/9901/files/thread-red-1003.png',
                'Red T-Shirts, Burgundy Products',
            ],
        ];
        
        foreach ($sampleRows as $row) {
            $csvContent .= implode(',', array_map(function($value) {
                // Escape commas, quotes, and newlines in CSV
                if (strpos($value, ',') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
                    $value = '"' . str_replace('"', '""', $value) . '"';
                }
                return $value;
            }, $row)) . "\n";
        }
        
        $filename = 'thread-colors-template-' . date('Y-m-d') . '.csv';
        
        return Response::streamDownload(function () use ($csvContent) {
            echo $csvContent;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}