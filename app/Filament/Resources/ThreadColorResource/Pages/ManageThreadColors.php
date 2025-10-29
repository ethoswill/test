<?php

namespace App\Filament\Resources\ThreadColorResource\Pages;

use App\Filament\Resources\ThreadColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Services\GoogleSheetsService;
use App\Models\ThreadColor;
use Filament\Forms;

class ManageThreadColors extends ManageRecords
{
    protected static string $resource = ThreadColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('bulkUploadImages')
                ->label('Bulk Upload Images')
                ->icon('heroicon-o-photo')
                ->color('warning')
                ->form([
                    Forms\Components\FileUpload::make('images')
                        ->label('Thread Color Images')
                        ->multiple()
                        ->image()
                        ->directory('thread-colors')
                        ->visibility('public')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048)
                        ->helperText('Upload multiple thread color images. Name files with thread numbers (e.g., 1500.jpg, 1508.png)')
                        ->required()
                ])
                ->action(function (array $data) {
                    try {
                        $uploadedCount = 0;
                        $updatedCount = 0;
                        
                        foreach ($data['images'] as $imagePath) {
                            // Extract thread number from filename
                            $filename = basename($imagePath);
                            $threadNumber = pathinfo($filename, PATHINFO_FILENAME);
                            
                            // Find matching thread color by number
                            $threadColor = ThreadColor::where('color_name', $threadNumber)
                                ->orWhere('color_code', $threadNumber)
                                ->first();
                            
                            if ($threadColor) {
                                $threadColor->update(['image_url' => $imagePath]);
                                $updatedCount++;
                            } else {
                                // Create new thread color if not found
                                ThreadColor::create([
                                    'color_name' => $threadNumber,
                                    'color_code' => $threadNumber,
                                    'image_url' => $imagePath,
                                ]);
                                $uploadedCount++;
                            }
                        }
                        
                        Notification::make()
                            ->title('Bulk Upload Successful!')
                            ->body("Updated {$updatedCount} existing thread colors and created {$uploadedCount} new ones.")
                            ->success()
                            ->send();
                            
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Upload Failed')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->tooltip('Upload multiple thread color images at once. Name files with thread numbers.'),
            Action::make('testGoogleSheetsConnection')
                ->label('Test Google Sheets Connection')
                ->icon('heroicon-o-link')
                ->color('info')
                ->action(function () {
                    try {
                        $googleSheetsService = new GoogleSheetsService();
                        $spreadsheetId = '1gTHgdksxGx7CThTbAENPJ44ndhCJBJPoEn0l1_68QK8';
                        
                        $result = $googleSheetsService->testConnection($spreadsheetId);
                        
                        if ($result['success']) {
                            Notification::make()
                                ->title('Connection Successful!')
                                ->body("Connected to: {$result['title']}. Available sheets: " . implode(', ', $result['sheets']))
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Connection Failed')
                                ->body('Error: ' . $result['error'])
                                ->danger()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Connection Error')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->tooltip('Test connection to Google Sheets'),
            Action::make('importWithRealImages')
                ->label('Import with Real Images from Google Sheets')
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('success')
                ->action(function () {
                    try {
                        $googleSheetsService = new GoogleSheetsService();
                        $spreadsheetId = '1gTHgdksxGx7CThTbAENPJ44ndhCJBJPoEn0l1_68QK8';
                        
                        // Try to get images from Google Sheets
                        $result = $googleSheetsService->downloadAndStoreImages($spreadsheetId, 'Madeira Swatches!A:B');
                        $threadColors = $result['threadColors'];
                        $downloadedCount = $result['downloadedCount'];
                        
                        if (empty($threadColors)) {
                            Notification::make()
                                ->title('No Data Found')
                                ->body('No thread colors found in the specified range')
                                ->warning()
                                ->send();
                            return;
                        }

                        // Clear existing data
                        ThreadColor::truncate();

                        // Import new data
                        $imported = 0;
                        foreach ($threadColors as $threadColor) {
                            ThreadColor::create($threadColor);
                            $imported++;
                        }

                        Notification::make()
                            ->title('Import Successful!')
                            ->body("Imported {$imported} thread colors. Downloaded {$downloadedCount} real images from Google Sheets!")
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import Failed')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->tooltip('Import thread colors and download real images from Google Sheets'),
            Action::make('downloadThreadColors')
                ->label('Download Thread Colors')
                ->icon('heroicon-o-arrow-down-tray')
                ->url('https://drive.google.com/uc?export=download&id=1tC7YLVxove4U8sY589lJzf3jqzYXGIsh')
                ->openUrlInNewTab()
                ->tooltip('The colors on the downloaded document might not 100% match the colors that the thread will be in person. For accurate thread colors check in person or download our Domestic Embroidery Thread Color Swatches'),
            Action::make('downloadDomesticSwatches')
                ->label('Download Domestic Embroidery Swatches')
                ->icon('heroicon-o-squares-2x2')
                ->url('https://docs.google.com/spreadsheets/d/1gTHgdksxGx7CThTbAENPJ44ndhCJBJPoEn0l1_68QK8/edit?usp=sharing')
                ->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ];
    }
}