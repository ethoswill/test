<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SockResource\Pages;
use App\Filament\Resources\SockResource\RelationManagers;
use App\Models\Sock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SockResource extends Resource
{
    protected static ?string $model = Sock::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Styles';
    protected static ?string $modelLabel = 'Sock';
    protected static ?string $pluralModelLabel = 'Socks';
    protected static ?string $navigationGroup = 'Socks';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Sock Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Sock Style')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Athletic Crew Socks, Dress Socks'),
                        Forms\Components\Textarea::make('description')
                            ->label('Bullet Points')
                            ->maxLength(1000)
                            ->rows(4)
                            ->placeholder('Enter each bullet point on a new line:' . PHP_EOL . '• Moisture-wicking technology' . PHP_EOL . '• Cushioned sole for comfort' . PHP_EOL . '• Reinforced heel and toe'),
                        Forms\Components\Textarea::make('images')
                            ->label('Sock Image URLs')
                            ->maxLength(1000)
                            ->placeholder('Enter image URLs (one per line):' . PHP_EOL . 'https://example.com/sock1.jpg' . PHP_EOL . 'https://example.com/sock2.jpg')
                            ->helperText('Enter 1-3 image URLs for the sock style (one URL per line)')
                            ->rows(3),
                    ])
                    ->columns(1),
                
                Forms\Components\Section::make('Specifications')
                    ->schema([
                        Forms\Components\TextInput::make('ribbing_height')
                            ->label('Height of Sock Ribbing')
                            ->maxLength(255)
                            ->placeholder('e.g., 2 inches, 5cm'),
                        Forms\Components\TextInput::make('fabric')
                            ->label('Fabric')
                            ->maxLength(255)
                            ->placeholder('e.g., Cotton Blend, Merino Wool'),
                        Forms\Components\TextInput::make('price')
                            ->label('Starting Price')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('0.00'),
                        Forms\Components\TextInput::make('minimums')
                            ->label('Minimums')
                            ->maxLength(255)
                            ->placeholder('e.g., 12 pairs, 24 pairs'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Sock Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Sock Style')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Bullet Points')
                            ->formatStateUsing(function (?string $state): string {
                                if (empty($state)) {
                                    return 'No description';
                                }
                                $lines = array_filter(array_map('trim', explode("\n", $state)));
                                return implode("\n• ", array_map(function($line) {
                                    return ltrim($line, '• -');
                                }, $lines));
                            })
                            ->markdown()
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('images')
                            ->label('Sock Image URLs')
                            ->formatStateUsing(function ($state) {
                                if (is_array($state) && !empty($state)) {
                                    return implode("\n", $state);
                                }
                                if (is_string($state)) {
                                    return $state;
                                }
                                return 'No images';
                            })
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
                
                Infolists\Components\Section::make('Specifications')
                    ->schema([
                        Infolists\Components\TextEntry::make('ribbing_height')
                            ->label('Height of Sock Ribbing'),
                        Infolists\Components\TextEntry::make('fabric')
                            ->label('Fabric'),
                        Infolists\Components\TextEntry::make('price')
                            ->label('Starting Price')
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('minimums')
                            ->label('Minimums'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Image')
                    ->height(120)
                    ->width(96)
                    ->circular(false)
                    ->defaultImageUrl('/images/placeholder-sock.png'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Sock Style')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('lg')
                    ->url(fn (Sock $record): string => route('filament.admin.resources.socks.view', $record))
                    ->color('primary'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Bullet Points')
                    ->wrap(false)
                    ->formatStateUsing(function (string $state): string {
                        // Split by line breaks and format each point with bullets
                        $lines = array_filter(array_map('trim', explode("\n", $state)));
                        return implode("<br>• ", array_map(function($line) {
                            // Remove existing bullets/dashes and add clean bullet
                            return ltrim($line, '• -');
                        }, $lines));
                    })
                    ->html(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Available Socks'),
                Tables\Filters\SelectFilter::make('material')
                    ->options([
                        'cotton' => 'Cotton',
                        'wool' => 'Wool',
                        'synthetic' => 'Synthetic',
                        'bamboo' => 'Bamboo',
                        'merino' => 'Merino Wool',
                    ]),
                Tables\Filters\SelectFilter::make('size')
                    ->options([
                        'XS' => 'Extra Small',
                        'S' => 'Small',
                        'M' => 'Medium',
                        'L' => 'Large',
                        'XL' => 'Extra Large',
                    ]),
            ])
            ->actions([
                // No actions - clicking the sock style name will navigate to view page
            ])
            ->checkIfRecordIsSelectableUsing(fn () => false)
            ->defaultSort('name', 'asc')
            ->paginated(false); // Show all socks on one page for info sheet feel
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\SocksGallery::route('/'),
            'create' => Pages\CreateSock::route('/create'),
            'view' => Pages\ViewSock::route('/{record}'),
            'edit' => Pages\EditSock::route('/{record}/edit'),
        ];
    }
}
