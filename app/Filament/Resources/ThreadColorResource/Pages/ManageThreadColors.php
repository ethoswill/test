<?php

namespace App\Filament\Resources\ThreadColorResource\Pages;

use App\Filament\Resources\ThreadColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Services\GoogleSheetsService;
use App\Services\GoogleDriveImageService;
use App\Models\ThreadColor;
use Filament\Forms;

class ManageThreadColors extends ManageRecords
{
    protected static string $resource = ThreadColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('testGoogleDriveConnection')
                ->label('Test Google Drive Connection')
                ->icon('heroicon-o-cloud')
                ->color('info')
                ->form([
                    Forms\Components\TextInput::make('google_drive_folder_url')
                        ->label('Google Drive Folder URL')
                        ->url()
                        ->required()
                        ->helperText('Paste the Google Drive folder URL here')
                        ->placeholder('https://drive.google.com/drive/folders/...')
                        ->default('https://drive.google.com/drive/folders/1RDevqNIwqVJixqs4VwJGb3GsakeP5kgl?usp=drive_link')
                ])
                ->action(function (array $data) {
                    try {
                        $googleDriveImageService = new GoogleDriveImageService();
                        $result = $googleDriveImageService->testConnection();
                        
                        if ($result['success']) {
                            Notification::make()
                                ->title('Google Drive Connection Successful!')
                                ->body("Found {$result['totalFiles']} total files, {$result['imageFiles']} image files. Sample files: " . implode(', ', array_column($result['sampleFiles'], 'name')))
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Google Drive Connection Failed')
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
                ->tooltip('Test connection to Google Drive folder'),
            Action::make('importFromGoogleDrive')
                ->label('Import from Google Drive Folder')
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('success')
                ->form([
                    Forms\Components\TextInput::make('google_drive_folder_url')
                        ->label('Google Drive Folder URL')
                        ->url()
                        ->required()
                        ->helperText('Paste the Google Drive folder URL here')
                        ->placeholder('https://drive.google.com/drive/folders/...')
                        ->default('https://drive.google.com/drive/folders/1RDevqNIwqVJixqs4VwJGb3GsakeP5kgl?usp=drive_link'),
                    Forms\Components\TextInput::make('limit')
                        ->label('Limit (optional)')
                        ->numeric()
                        ->default(50)
                        ->helperText('Number of thread colors to process (default: 50)')
                ])
                ->action(function (array $data) {
                    try {
                        set_time_limit(300); // 5 minutes
                        
                        $googleDriveImageService = new GoogleDriveImageService();
                        $googleSheetsService = new GoogleSheetsService();
                        
                        // Get thread colors from Google Sheets
                        $spreadsheetId = '1gTHgdksxGx7CThTbAENPJ44ndhCJBJPoEn0l1_68QK8';
                        $threadColors = $googleSheetsService->getThreadColorsFromSheet($spreadsheetId, 'Madeira Swatches!A:B');
                        
                        if (empty($threadColors)) {
                            Notification::make()
                                ->title('No Data Found')
                                ->body('No thread colors found in Google Sheets')
                                ->warning()
                                ->send();
                            return;
                        }

                        // Limit processing
                        $limit = $data['limit'] ?? 50;
                        $threadColors = array_slice($threadColors, 0, $limit);

                        // Clear existing data
                        ThreadColor::truncate();

                        $imported = 0;
                        $downloadedCount = 0;
                        
                        foreach ($threadColors as $threadColor) {
                            // Try to get image from Google Drive
                            $imagePath = $googleDriveImageService->downloadAndStoreImage($threadColor['color_code']);
                            
                            if ($imagePath) {
                                $threadColor['image_url'] = $imagePath;
                                $downloadedCount++;
                            }

                            ThreadColor::create($threadColor);
                            $imported++;
                            
                            // Small delay to prevent rate limiting
                            usleep(100000); // 0.1 second
                        }

                        Notification::make()
                            ->title('Import Successful!')
                            ->body("Imported {$imported} thread colors. Downloaded {$downloadedCount} images from Google Drive!")
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
                ->tooltip('Import thread colors and download images from Google Drive folder'),
        ];
    }
}