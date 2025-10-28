<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThreadColorResource\Pages;
use App\Models\ThreadColor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ThreadColorResource extends Resource
{
    protected static ?string $model = ThreadColor::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationLabel = 'Thread Colors';

    protected static ?string $navigationGroup = 'Embroidery';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('color_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Color Name'),
                Forms\Components\TextInput::make('color_code')
                    ->maxLength(255)
                    ->label('Color Code'),
                Forms\Components\TextInput::make('hex_code')
                    ->maxLength(255)
                    ->label('Hex Code')
                    ->helperText('e.g., #FF0000'),
                Forms\Components\TextInput::make('image_url')
                    ->maxLength(255)
                    ->label('Image URL')
                    ->url()
                    ->helperText('URL to thread color image'),
                Forms\Components\TextInput::make('manufacturer')
                    ->maxLength(255)
                    ->label('Manufacturer'),
                Forms\Components\TextInput::make('thread_type')
                    ->maxLength(255)
                    ->label('Thread Type'),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->maxLength(1000)
                    ->label('Description'),
                Forms\Components\TextInput::make('availability')
                    ->maxLength(255)
                    ->label('Availability'),
                Forms\Components\Textarea::make('usage_notes')
                    ->rows(3)
                    ->maxLength(500)
                    ->label('Usage Notes'),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->label('Sort Order'),
                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('color_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('color_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hex_code'),
                Tables\Columns\TextColumn::make('manufacturer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('thread_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('availability')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
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
            'index' => Pages\ManageThreadColors::route('/'),
            'create' => Pages\CreateThreadColor::route('/create'),
            'edit' => Pages\EditThreadColor::route('/{record}/edit'),
        ];
    }
}

