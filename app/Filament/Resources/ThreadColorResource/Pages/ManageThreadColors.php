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