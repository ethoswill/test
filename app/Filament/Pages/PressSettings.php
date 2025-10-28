<?php

namespace App\Filament\Pages;

use App\Models\PressSetting;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class PressSettings extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    
    protected static ?string $navigationLabel = 'Press Settings';
    
    protected static ?string $navigationGroup = 'In House Print';
    
    protected static ?int $navigationSort = 5;
    
    protected static string $view = 'filament.pages.press-settings';
    
    protected static string $routePath = 'press-settings';
    
    protected static ?string $title = 'Press Settings';

    public function getHeaderActions(): array
    {
        return [
            Action::make('downloadPressSettings')
                ->label('Download Press Settings')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    // Add download functionality here
                    // For now, this is a placeholder
                }),
            Action::make('create')
                ->label('Create Setting')
                ->icon('heroicon-o-plus-circle')  // You can change this to any Heroicon
                ->form([
                    \Filament\Forms\Components\TextInput::make('fabric')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('temperature')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('pressure')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('time')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('number_of_presses')
                        ->label('Number of Presses')
                        ->required()
                        ->maxLength(255),
                ])
                ->action(function (array $data) {
                    PressSetting::create($data);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Press setting created successfully!')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(PressSetting::query())
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('fabric')
                    ->label(fn () => new \Illuminate\Support\HtmlString('👕 Fabric'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->action(
                        \Filament\Tables\Actions\EditAction::make()
                            ->form([
                                \Filament\Forms\Components\TextInput::make('fabric')
                                    ->required()
                                    ->maxLength(255),
                                \Filament\Forms\Components\TextInput::make('temperature')
                                    ->required()
                                    ->maxLength(255),
                                \Filament\Forms\Components\TextInput::make('pressure')
                                    ->required()
                                    ->maxLength(255),
                                \Filament\Forms\Components\TextInput::make('time')
                                    ->required()
                                    ->maxLength(255),
                                \Filament\Forms\Components\TextInput::make('number_of_presses')
                                    ->label('Number of Presses')
                                    ->required()
                                    ->maxLength(255),
                            ])
                    ),
                \Filament\Tables\Columns\TextColumn::make('temperature')
                    ->label(fn () => new \Illuminate\Support\HtmlString('🔥 Temperature'))
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('pressure')
                    ->label(fn () => new \Illuminate\Support\HtmlString('⬇️ Pressure'))
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('time')
                    ->label(fn () => new \Illuminate\Support\HtmlString('⏱️ Time'))
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('number_of_presses')
                    ->label(fn () => new \Illuminate\Support\HtmlString('📦 Number of Presses'))
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('fabric')
            ->checkIfRecordIsSelectableUsing(fn () => false);
    }
}

