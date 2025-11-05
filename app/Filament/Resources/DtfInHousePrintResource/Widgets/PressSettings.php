<?php

namespace App\Filament\Resources\DtfInHousePrintResource\Widgets;

use App\Models\PressSetting;
use Filament\Widgets\TableWidget;
use Filament\Tables\Table;
use Filament\Forms;

class PressSettings extends TableWidget
{
    protected static string $view = 'filament.resources.dtf-in-house-print-resource.widgets.press-settings';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 9;

    public function table(Table $table): Table
    {
        return $table
            ->query(PressSetting::query())
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('fabric')
                    ->label('Fabric')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
                \Filament\Tables\Columns\TextColumn::make('temperature')
                    ->label('Temperature')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('pressure')
                    ->label('Pressure')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('time')
                    ->label('Time')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('number_of_presses')
                    ->label('Number of Presses')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('fabric')
            ->headerActions([
                \Filament\Tables\Actions\Action::make('create')
                    ->label('Add Press Setting')
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        Forms\Components\TextInput::make('fabric')
                            ->required()
                            ->maxLength(255)
                            ->label('Fabric'),
                        Forms\Components\TextInput::make('temperature')
                            ->required()
                            ->maxLength(255)
                            ->label('Temperature'),
                        Forms\Components\TextInput::make('pressure')
                            ->required()
                            ->maxLength(255)
                            ->label('Pressure'),
                        Forms\Components\TextInput::make('time')
                            ->required()
                            ->maxLength(255)
                            ->label('Time'),
                        Forms\Components\TextInput::make('number_of_presses')
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
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('fabric')
                            ->required()
                            ->maxLength(255)
                            ->label('Fabric'),
                        Forms\Components\TextInput::make('temperature')
                            ->required()
                            ->maxLength(255)
                            ->label('Temperature'),
                        Forms\Components\TextInput::make('pressure')
                            ->required()
                            ->maxLength(255)
                            ->label('Pressure'),
                        Forms\Components\TextInput::make('time')
                            ->required()
                            ->maxLength(255)
                            ->label('Time'),
                        Forms\Components\TextInput::make('number_of_presses')
                            ->label('Number of Presses')
                            ->required()
                            ->maxLength(255),
                    ]),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->checkIfRecordIsSelectableUsing(fn () => false);
    }
}

