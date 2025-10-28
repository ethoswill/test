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
                    ->label('Thread Number'),
                Forms\Components\TextInput::make('color_code')
                    ->required()
                    ->maxLength(255)
                    ->label('Color Code'),
                Forms\Components\TextInput::make('image_url')
                    ->maxLength(255)
                    ->label('Image URL')
                    ->url()
                    ->helperText('URL to thread color image from Google Sheets'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('color_name')
                    ->label('Thread Number')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => route('filament.admin.resources.thread-colors.edit', $record))
                    ->color('primary'),
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Swatch Image'),
            ])
            ->filters([
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('color_name');
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

