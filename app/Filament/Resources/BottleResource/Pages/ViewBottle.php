<?php

namespace App\Filament\Resources\BottleResource\Pages;

use App\Filament\Resources\BottleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewBottle extends ViewRecord
{
    protected static string $resource = BottleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Bottle Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Bottle Style')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Bullet Points')
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return 'No bullet points added.';
                                }
                                $lines = array_filter(array_map('trim', explode("\n", $state)));
                                return '• ' . implode("\n• ", $lines);
                            })
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Specifications')
                    ->schema([
                        Infolists\Components\TextEntry::make('material')
                            ->label('Material'),
                        Infolists\Components\TextEntry::make('size')
                            ->label('Size'),
                        Infolists\Components\TextEntry::make('price')
                            ->label('Starting Price')
                            ->formatStateUsing(fn ($state) => $state ? '$' . number_format($state, 2) : 'N/A'),
                    ])
                    ->columns(3),
            ]);
    }
}

