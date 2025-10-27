<?php

namespace App\Filament\Resources\GripResource\Pages;

use App\Filament\Resources\GripResource;
use App\Models\Grip;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\Action as TableAction;

class GripsList extends ListRecords
{
    protected static string $resource = GripResource::class;
    protected static ?string $title = 'Grip Styles';

    public function getHeaderActions(): array
    {
        return [
            Action::make('add_grip')
                ->label('Add New Grip')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->form([
                    Section::make('Grip Information')
                        ->schema([
                            TextInput::make('name')
                                ->label('Grip Name')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g., Rubber Grip, Silicon Grip'),
                            Textarea::make('description')
                                ->label('Bullet Points')
                                ->maxLength(1000)
                                ->rows(4)
                                ->placeholder('Enter each bullet point on a new line:' . PHP_EOL . '• Non-slip material' . PHP_EOL . '• Durable construction' . PHP_EOL . '• Easy to apply'),
                            Textarea::make('images')
                                ->label('Grip Image URLs')
                                ->maxLength(1000)
                                ->placeholder('Enter image URLs (one per line):' . PHP_EOL . 'https://example.com/grip1.jpg' . PHP_EOL . 'https://example.com/grip2.jpg')
                                ->helperText('Enter 1-3 image URLs for the grip (one URL per line)')
                                ->rows(3),
                        ])
                        ->columns(1),
                    
                    Section::make('Specifications')
                        ->schema([
                            TextInput::make('material')
                                ->label('Material')
                                ->maxLength(255)
                                ->placeholder('e.g., Rubber, Silicon, PVC'),
                            TextInput::make('price')
                                ->label('Starting Price')
                                ->numeric()
                                ->prefix('$')
                                ->placeholder('0.00'),
                        ])
                        ->columns(2),
                ])
                ->action(function (array $data): void {
                    Grip::create([
                        'name' => $data['name'],
                        'description' => $data['description'],
                        'images' => $data['images'],
                        'material' => $data['material'] ?? null,
                        'price' => $data['price'] ?: 0,
                        'is_active' => true,
                    ]);

                    Notification::make()
                        ->title('Grip added successfully!')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Image')
                    ->height(120)
                    ->width(96)
                    ->circular(false)
                    ->defaultImageUrl('/images/placeholder-grip.png'),
                TextColumn::make('name')
                    ->label('Grip Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('lg')
                    ->url(fn (Grip $record): string => route('filament.admin.resources.grips.view', $record))
                    ->color('primary'),
                TextColumn::make('description')
                    ->label('Bullet Points')
                    ->limit(100)
                    ->wrap()
                    ->formatStateUsing(function (string $state): string {
                        // Split by line breaks and format each point
                        $lines = array_filter(array_map('trim', explode("\n", $state)));
                        return implode("\n• ", array_map(function($line) {
                            // Remove existing bullets/dashes and add clean bullet
                            return ltrim($line, '• -');
                        }, $lines));
                    })
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 100) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                // No actions - clicking the grip name will navigate to view page
            ])
            ->headerActions([
                TableAction::make('download_all')
                    ->label('Download All Grip CADs')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function () {
                        // Google Drive file ID from the URL
                        $fileId = '1uamwwaWoM564xeKwEzDxKGDyZpme1cA0';
                        
                        // Create a zip file
                        $zipPath = storage_path('app/temp/grip-cads-' . now()->format('Y-m-d-His') . '.zip');
                        $zip = new \ZipArchive();
                        
                        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                            return Notification::make()
                                ->danger()
                                ->title('Error creating ZIP file')
                                ->send();
                        }
                        
                        // Download the file from Google Drive
                        $downloadUrl = "https://drive.google.com/uc?export=download&id=" . $fileId;
                        $fileContent = @file_get_contents($downloadUrl);
                        
                        if ($fileContent !== false) {
                            $zip->addFromString('grip-cads.zip', $fileContent);
                        } else {
                            return Notification::make()
                                ->danger()
                                ->title('Error downloading file')
                                ->body('Unable to download the file from Google Drive.')
                                ->send();
                        }
                        
                        $zip->close();
                        
                        return response()->download($zipPath)->deleteFileAfterSend(true);
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

