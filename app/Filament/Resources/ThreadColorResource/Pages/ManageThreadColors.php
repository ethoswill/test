<?php

namespace App\Filament\Resources\ThreadColorResource\Pages;

use App\Filament\Resources\ThreadColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class ManageThreadColors extends ManageRecords
{
    protected static string $resource = ThreadColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('connectGoogleSheets')
                ->label('Connect Google Sheets')
                ->icon('heroicon-o-link')
                ->color('success')
                ->action(function () {
                    try {
                        // Run the import command
                        $exitCode = Artisan::call('thread-colors:import-from-sheets', [
                            'spreadsheet_id' => '1gTHgdksxGx7CThTbAENPJ44ndhCJBJPoEn0l1_68QK8',
                            '--range' => 'Madeira Swatches!A:B'
                        ]);

                        if ($exitCode === 0) {
                            Notification::make()
                                ->title('Success!')
                                ->body('Thread colors imported successfully from Google Sheets')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Error')
                                ->body('Failed to import thread colors. Please check the logs.')
                                ->danger()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Error importing thread colors: ' . $e->getMessage())
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

