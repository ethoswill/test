<?php

namespace App\Filament\Resources\SockResource\Pages;

use App\Filament\Resources\SockResource;
use App\Filament\Resources\SockResource\Widgets\SocksHeader;
use App\Models\Sock;
use App\Models\TeamNote;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class SocksGallery extends ListRecords
{
    protected static string $resource = SockResource::class;
    protected static ?string $title = 'Sock Styles';

    public function getHeaderWidgets(): array
    {
        return [
            SocksHeader::class,
        ];
    }

    public function getHeaderActions(): array
    {
        $actions = [
            Action::make('add_sock')
                ->label('Add New Sock Style')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->form([
                    Section::make('Sock Information')
                        ->schema([
                            TextInput::make('name')
                                ->label('Sock Style')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g., Athletic Crew Socks, Dress Socks'),
                            Textarea::make('description')
                                ->label('Bullet Points')
                                ->maxLength(1000)
                                ->rows(4)
                                ->placeholder('Enter each bullet point on a new line:' . PHP_EOL . '• Moisture-wicking technology' . PHP_EOL . '• Cushioned sole for comfort' . PHP_EOL . '• Reinforced heel and toe'),
                            Textarea::make('images')
                                ->label('Sock Cover Image')
                                ->maxLength(1000)
                                ->placeholder('Enter cover image URL:' . PHP_EOL . 'https://example.com/sock-cover.jpg')
                                ->helperText('Enter the main cover image URL for this sock style')
                                ->rows(2),
                        ])
                        ->columns(1),
                    
                    Section::make('Specifications')
                        ->schema([
                            TextInput::make('ribbing_height')
                                ->label('Height of Sock Ribbing')
                                ->maxLength(255)
                                ->placeholder('e.g., 2 inches, 5cm'),
                            TextInput::make('fabric')
                                ->label('Fabric')
                                ->maxLength(255)
                                ->placeholder('e.g., Cotton Blend, Merino Wool'),
                            TextInput::make('price')
                                ->label('Starting Price')
                                ->numeric()
                                ->prefix('$')
                                ->placeholder('0.00'),
                            TextInput::make('minimums')
                                ->label('Minimums')
                                ->maxLength(255)
                                ->placeholder('e.g., 12 pairs, 24 pairs'),
                        ])
                        ->columns(2),
                    
                    Section::make('Gallery')
                        ->schema([
                            Textarea::make('gallery_images')
                                ->label('Gallery Images')
                                ->maxLength(2000)
                                ->placeholder('Enter gallery image URLs (one per line):' . PHP_EOL . 'https://example.com/gallery1.jpg' . PHP_EOL . 'https://example.com/gallery2.jpg' . PHP_EOL . 'https://example.com/gallery3.jpg')
                                ->helperText('Enter gallery image URLs, one per line. Each line will display as a separate image in the gallery (4 columns on desktop)')
                                ->rows(6),
                        ])
                        ->columns(1),
                ])
                ->action(function (array $data): void {
                    Sock::create([
                        'name' => $data['name'],
                        'description' => $data['description'],
                        'images' => $data['images'],
                        'gallery_images' => $data['gallery_images'] ?? null,
                        'ribbing_height' => $data['ribbing_height'] ?? null,
                        'fabric' => $data['fabric'] ?? null,
                        'price' => $data['price'] ?: 0,
                        'minimums' => $data['minimums'] ?? null,
                        'is_active' => true, // Always available
                    ]);

                    Notification::make()
                        ->title('Sock style added successfully!')
                        ->success()
                        ->send();
                }),
        ];
        $actions[] = Action::make('download_all_cads')
            ->label('Download All Sock CADs')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('info')
            ->url('https://cdn.shopify.com/s/files/1/0609/4752/9901/files/Sock_Style_CADs.pdf?v=1761517695', shouldOpenInNewTab: true);

        // Add team notes edit action
        $teamNote = TeamNote::firstOrCreate(['page' => 'socks'], ['content' => '']);
        
        $actions[] = Action::make('edit_team_notes')
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
                
                $teamNote = TeamNote::firstOrNew(['page' => 'socks']);
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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Image')
                    ->height(120)
                    ->width(96)
                    ->circular(false)
                    ->defaultImageUrl('/images/placeholder-sock.png'),
                TextColumn::make('name')
                    ->label('Sock Style')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('lg')
                    ->url(fn (Sock $record): string => route('filament.admin.resources.socks.view', $record))
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
                // No actions - clicking the sock style name will navigate to view page
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}