<?php

namespace App\Filament\Resources\ThreadColorResource\Pages;

use App\Filament\Resources\ThreadColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Services\GoogleSheetsService;
use App\Models\ThreadColor;

class ManageThreadColors extends ManageRecords
{
    protected static string $resource = ThreadColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
            Action::make('importFromGoogleSheets')
                ->label('Import from Google Sheets')
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('success')
                ->action(function () {
                    try {
                        $googleSheetsService = new GoogleSheetsService();
                        $spreadsheetId = '1gTHgdksxGx7CThTbAENPJ44ndhCJBJPoEn0l1_68QK8';
                        
                        $threadColors = $googleSheetsService->getThreadColorsFromSheet($spreadsheetId, 'Madeira Swatches!A:B');
                        
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
                            ->body("Imported {$imported} thread colors from Google Sheets")
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
                ->tooltip('Import all thread colors with image URLs from Google Sheets'),
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