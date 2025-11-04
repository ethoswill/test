<?php

namespace App\Filament\Resources\PuffPrintColorResource\Pages;

use App\Filament\Resources\PuffPrintColorResource;
use App\Filament\Resources\PuffPrintColorResource\Widgets\PuffPrintHeader;
use App\Filament\Resources\PuffPrintColorResource\Widgets\PuffPrintMachineSettings;
use App\Filament\Resources\PuffPrintColorResource\Widgets\PuffPrintMinimums;
use App\Filament\Resources\PuffPrintColorResource\Widgets\PuffPrintLeadTime;
use App\Filament\Resources\PuffPrintColorResource\Widgets\PuffPrintPricing;
use App\Filament\Resources\PuffPrintColorResource\Widgets\PuffPrintVisualReference;
use App\Models\PuffPrintColor;
use App\Models\TeamNote;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ListPuffPrintColors extends ListRecords
{
    protected static string $resource = PuffPrintColorResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make()
                ->color('success'),
            Actions\Action::make('upload_color_codes')
                ->label('Upload Color Codes')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->form([
                    FileUpload::make('color_file')
                        ->label('Color Codes File')
                        ->required()
                        ->acceptedFileTypes(['text/plain', 'text/csv'])
                        ->helperText('Upload a text file with format: "Color Name - #hexcode" (one per line)')
                        ->disk('local')
                        ->directory('color-imports')
                        ->visibility('private'),
                ])
                ->action(function (array $data) {
                    try {
                        if (empty($data['color_file'])) {
                            Notification::make()
                                ->title('No file uploaded')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        $fileContent = null;
                        $fileToCleanup = null;
                        $disk = Storage::disk('local');
                        
                        // Handle different file upload formats
                        $uploadedFile = $data['color_file'];
                        
                        // Handle array (if multiple files allowed)
                        if (is_array($uploadedFile)) {
                            $uploadedFile = $uploadedFile[0] ?? null;
                        }
                        
                        if (!$uploadedFile) {
                            Notification::make()
                                ->title('No file uploaded')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        // If it's a TemporaryUploadedFile object, store it first then read it
                        if ($uploadedFile instanceof TemporaryUploadedFile) {
                            // Store the file to our specified location first
                            // This ensures we have a known path to read from
                            $filename = $uploadedFile->getClientOriginalName() ?: 'color-import-' . time() . '.txt';
                            
                            try {
                                // Store the file using storeAs (private visibility since disk is 'local')
                                $storedPath = $uploadedFile->storeAs('color-imports', $filename, 'local');
                                
                                if ($storedPath && $disk->exists($storedPath)) {
                                    $fileContent = $disk->get($storedPath);
                                    $fileToCleanup = $storedPath;
                                } else {
                                    // Fallback: try reading directly from the TemporaryUploadedFile
                                    try {
                                        $fileContent = $uploadedFile->get();
                                        $fileToCleanup = $uploadedFile->getPathname();
                                    } catch (\Exception $e) {
                                        // Last resort: try getRealPath
                                        $tempPath = $uploadedFile->getRealPath();
                                        if ($tempPath && file_exists($tempPath)) {
                                            $fileContent = file_get_contents($tempPath);
                                            $fileToCleanup = $tempPath;
                                        }
                                    }
                                }
                            } catch (\Exception $e) {
                                // If storeAs fails, try reading directly
                                try {
                                    $fileContent = $uploadedFile->get();
                                    $fileToCleanup = $uploadedFile->getPathname();
                                } catch (\Exception $e2) {
                                    $tempPath = $uploadedFile->getRealPath();
                                    if ($tempPath && file_exists($tempPath)) {
                                        $fileContent = file_get_contents($tempPath);
                                        $fileToCleanup = $tempPath;
                                    }
                                }
                            }
                        } 
                        // If it's a string path
                        elseif (is_string($uploadedFile)) {
                            // The path might be relative to the disk root or absolute
                            // Try multiple path formats
                            $possiblePaths = [
                                $uploadedFile,
                                'color-imports/' . $uploadedFile,
                                'color-imports/' . basename($uploadedFile),
                                str_replace('color-imports/', '', $uploadedFile),
                                str_replace('color-imports/', '', basename($uploadedFile)),
                                ltrim($uploadedFile, '/'),
                            ];
                            
                            $foundPath = null;
                            foreach ($possiblePaths as $path) {
                                $path = ltrim($path, '/');
                                if ($disk->exists($path)) {
                                    $foundPath = $path;
                                    break;
                                }
                            }
                            
                            if ($foundPath) {
                                $fileContent = $disk->get($foundPath);
                                $fileToCleanup = $foundPath;
                            } else {
                                // Try full paths (disk root is storage_path('app/private'))
                                $fullPaths = [
                                    storage_path('app/private/' . ltrim($uploadedFile, '/')),
                                    storage_path('app/private/color-imports/' . basename($uploadedFile)),
                                    storage_path('app/private/color-imports/' . $uploadedFile),
                                    storage_path('app/livewire-tmp/' . basename($uploadedFile)),
                                    storage_path('app/' . ltrim($uploadedFile, '/')),
                                ];
                                
                                foreach ($fullPaths as $fullPath) {
                                    if (file_exists($fullPath)) {
                                        $fileContent = file_get_contents($fullPath);
                                        $fileToCleanup = $fullPath;
                                        break;
                                    }
                                }
                            }
                        }
                        
                        if (!$fileContent) {
                            // Provide detailed error with attempted paths
                            $debugInfo = '';
                            if (is_string($uploadedFile)) {
                                $debugInfo = 'Attempted path: ' . $uploadedFile;
                            } elseif ($uploadedFile instanceof TemporaryUploadedFile) {
                                $debugInfo = 'File: ' . $uploadedFile->getFilename();
                            }
                            
                            Notification::make()
                                ->title('File not found')
                                ->body('Could not read uploaded file. ' . $debugInfo)
                                ->danger()
                                ->send();
                            return;
                        }
                        $lines = explode("\n", $fileContent);
                        
                        $updated = 0;
                        $created = 0;
                        $skipped = 0;
                        $errors = [];
                        
                        foreach ($lines as $lineNumber => $line) {
                            $line = trim($line);
                            
                            // Skip empty lines and header lines
                            if (empty($line) || strpos($line, '===') !== false || strpos($line, 'Puff Print Colors') !== false) {
                                continue;
                            }
                            
                            // Parse format: "Color Name - #hexcode"
                            if (preg_match('/^(.+?)\s*-\s*(#?[0-9a-fA-F]{6}|No hex code)$/i', $line, $matches)) {
                                $colorName = trim($matches[1]);
                                $hexCode = trim($matches[2]);
                                
                                // Skip if no hex code
                                if ($hexCode === 'No hex code' || empty($hexCode)) {
                                    $skipped++;
                                    continue;
                                }
                                
                                // Ensure hex code starts with #
                                if (!str_starts_with($hexCode, '#')) {
                                    $hexCode = '#' . $hexCode;
                                }
                                
                                // Validate hex code format
                                if (!preg_match('/^#[0-9a-fA-F]{6}$/', $hexCode)) {
                                    $errors[] = "Line " . ($lineNumber + 1) . ": Invalid hex code format for '{$colorName}'";
                                    continue;
                                }
                                
                                // Find or create color
                                $color = PuffPrintColor::firstOrNew(['name' => $colorName]);
                                
                                if ($color->exists) {
                                    $color->hex_code = $hexCode;
                                    $color->save();
                                    $updated++;
                                } else {
                                    $color->hex_code = $hexCode;
                                    $color->save();
                                    $created++;
                                }
                            } else {
                                $errors[] = "Line " . ($lineNumber + 1) . ": Invalid format - '{$line}'";
                            }
                        }
                        
                        // Clean up uploaded file
                        if ($fileToCleanup) {
                            try {
                                if (is_string($fileToCleanup) && $disk->exists($fileToCleanup)) {
                                    $disk->delete($fileToCleanup);
                                } elseif (file_exists($fileToCleanup)) {
                                    @unlink($fileToCleanup);
                                }
                            } catch (\Exception $e) {
                                // Ignore cleanup errors
                            }
                        }
                        
                        $message = "Color codes import completed! ";
                        $message .= "Updated: {$updated} color(s). ";
                        if ($created > 0) {
                            $message .= "Created: {$created} new color(s). ";
                        }
                        if ($skipped > 0) {
                            $message .= "Skipped: {$skipped} line(s) without hex codes. ";
                        }
                        if (!empty($errors)) {
                            $errorCount = count($errors);
                            $message .= "Errors: {$errorCount} line(s). ";
                            if ($errorCount <= 5) {
                                $message .= "Errors: " . implode(', ', $errors);
                            } else {
                                $message .= "First 5 errors: " . implode(', ', array_slice($errors, 0, 5)) . " and " . ($errorCount - 5) . " more.";
                            }
                        }
                        
                        Notification::make()
                            ->title($message)
                            ->success()
                            ->send();
                            
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->modalWidth('2xl'),
            Actions\Action::make('purchase_puff_print')
                ->label('Purchase Puff Print')
                ->icon('heroicon-o-shopping-cart')
                ->color('info')
                ->url('https://heattransfervinyl4u.com/collections/puff-htv/products/ht-puff-20-roll-yard', shouldOpenInNewTab: true),
        ];

        // Add team notes edit action
        $teamNote = TeamNote::firstOrCreate(['page' => 'puff-print-colors'], ['content' => '']);
        
        $actions[] = Actions\Action::make('edit_team_notes')
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
                
                $teamNote = TeamNote::firstOrNew(['page' => 'puff-print-colors']);
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

    protected function getHeaderWidgets(): array
    {
        return [
            PuffPrintHeader::class,
            PuffPrintMinimums::class,
            PuffPrintLeadTime::class,
            PuffPrintPricing::class,
            PuffPrintMachineSettings::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            PuffPrintVisualReference::class,
        ];
    }
}
