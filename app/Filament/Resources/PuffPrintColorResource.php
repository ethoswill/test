<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PuffPrintColorResource\Pages;
use App\Filament\Resources\PuffPrintColorResource\RelationManagers;
use App\Models\PuffPrintColor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PuffPrintColorResource extends Resource
{
    protected static ?string $model = PuffPrintColor::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationLabel = 'Puff Print Color Options';
    protected static ?string $modelLabel = 'Puff Print Color';
    protected static ?string $pluralModelLabel = 'Puff Print Colors';
    protected static ?string $navigationGroup = 'In House Print';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Color Name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('e.g., Apple Green, Navy Blue'),
                Forms\Components\TextInput::make('hex_code')
                    ->label('Hex Code')
                    ->maxLength(255)
                    ->placeholder('e.g., #FF5733')
                    ->helperText('Enter the hex color code (e.g., #FF5733)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Color Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hex_code')
                    ->label('Hex Code')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        if ($state) {
                            return new \Illuminate\Support\HtmlString(
                                '<div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background-color: ' . $state . '; border: 1px solid #ddd;"></div>
                                    <span>' . $state . '</span>
                                </div>'
                            );
                        }
                        return '-';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc');
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
            'index' => Pages\ListPuffPrintColors::route('/'),
            'create' => Pages\CreatePuffPrintColor::route('/create'),
            'edit' => Pages\EditPuffPrintColor::route('/{record}/edit'),
        ];
    }
}
