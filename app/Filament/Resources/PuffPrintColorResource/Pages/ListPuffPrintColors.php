<?php

namespace App\Filament\Resources\PuffPrintColorResource\Pages;

use App\Filament\Resources\PuffPrintColorResource;
use App\Filament\Resources\PuffPrintColorResource\Widgets\PuffPrintHeader;
use App\Filament\Resources\PuffPrintColorResource\Widgets\PuffPrintMachineSettings;
use App\Models\PuffPrintColor;
use App\Models\TeamNote;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

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
                        $filePath = storage_path('app/' . $data['color_file']);
                        
                        if (!file_exists($filePath)) {
                            Notification::make()
                                ->title('File not found')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        $fileContent = file_get_contents($filePath);
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
                        if (file_exists($filePath)) {
                            unlink($filePath);
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
            PuffPrintMachineSettings::class,
        ];
    }
}
