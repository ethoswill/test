<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeggingsDesignResource\Pages;
use App\Models\LeggingsDesign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeggingsDesignResource extends Resource
{
    protected static ?string $model = LeggingsDesign::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'Embroidery';

    protected static ?string $modelLabel = 'Design';

    protected static ?string $pluralModelLabel = 'Designs';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('designer_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('design_title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('design_description')
                    ->rows(3),
                Forms\Components\Select::make('design_category')
                    ->options([
                        'leggings' => 'Leggings',
                        'yoga' => 'Yoga',
                        'athletic' => 'Athletic',
                    ]),
                Forms\Components\TextInput::make('submission_status')
                    ->default('draft'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('designer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('design_title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('design_category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('submission_status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'draft' => 'gray',
                        'submitted' => 'info',
                        'under_review' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('submission_status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'under_review' => 'Under Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLeggingsDesigns::route('/'),
            'create' => Pages\CreateLeggingsDesign::route('/create'),
            'edit' => Pages\EditLeggingsDesign::route('/{record}/edit'),
        ];
    }
}

