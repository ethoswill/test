<?php

namespace App\Filament\Resources\ThreadColorResource\Pages;

use App\Filament\Resources\ThreadColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms;
use App\Models\ThreadColor;

class ManageThreadColors extends ManageRecords
{
    protected static string $resource = ThreadColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importFromCSV')
                ->label('Import Thread Colors')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    Forms\Components\FileUpload::make('csv_file')
                        ->label('CSV File')
                        ->acceptedFileTypes(['text/csv', 'application/csv'])
                        ->required()
                        ->helperText('Upload a CSV file with columns: Thread Number, Image URL')
                ])
                ->action(function (array $data) {
                    try {
                        $filePath = storage_path('app/public/' . $data['csv_file']);
                        
                        if (!file_exists($filePath)) {
                            Notification::make()
                                ->title('Error')
                                ->body('File not found')
                                ->danger()
                                ->send();
                            return;
                        }

                        $handle = fopen($filePath, 'r');
                        if (!$handle) {
                            Notification::make()
                                ->title('Error')
                                ->body('Could not open CSV file')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Skip header row
                        fgetcsv($handle);

                        // Clear existing data
                        ThreadColor::truncate();

                        $imported = 0;
                        while (($row = fgetcsv($handle)) !== false) {
                            if (count($row) >= 2) {
                                $colorCode = trim($row[0]);
                                $imageUrl = trim($row[1]);

                                ThreadColor::create([
                                    'color_name' => $colorCode,
                                    'color_code' => $colorCode,
                                    'image_url' => $imageUrl ?: 'https://via.placeholder.com/120x59?text=' . $colorCode,
                                ]);
                                $imported++;
                            }
                        }

                        fclose($handle);

                        Notification::make()
                            ->title('Success!')
                            ->body("Imported {$imported} thread colors successfully")
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Error importing thread colors: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->tooltip('Upload a CSV file to import thread colors with image URLs'),
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

