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
            Actions\Action::make('previous')
                ->label('Previous')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(function () {
                    // Try to get previous bottle
                    $previousBottle = \App\Models\Bottle::where('id', '<', $this->record->id)
                        ->orderBy('id', 'desc')
                        ->first();
                    
                    // If no previous bottle, loop to the last bottle
                    if (!$previousBottle) {
                        $previousBottle = \App\Models\Bottle::orderBy('id', 'desc')->first();
                    }
                    
                    if ($previousBottle) {
                        return $this->getResource()::getUrl('view', ['record' => $previousBottle]);
                    }
                    return null;
                })
                ->tooltip('View previous bottle'),
            Actions\Action::make('next')
                ->label('Next')
                ->icon('heroicon-o-arrow-right')
                ->color('gray')
                ->url(function () {
                    // Try to get next bottle
                    $nextBottle = \App\Models\Bottle::where('id', '>', $this->record->id)
                        ->orderBy('id', 'asc')
                        ->first();
                    
                    // If no next bottle, loop to the first bottle
                    if (!$nextBottle) {
                        $nextBottle = \App\Models\Bottle::orderBy('id', 'asc')->first();
                    }
                    
                    if ($nextBottle) {
                        return $this->getResource()::getUrl('view', ['record' => $nextBottle]);
                    }
                    return null;
                })
                ->tooltip('View next bottle'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Bottle Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('images')
                            ->label('Bottle Image')
                            ->formatStateUsing(function ($state, $record) {
                                $images = $record->images ?? [];
                                if (empty($images)) {
                                    return new \Illuminate\Support\HtmlString('<p class="text-gray-500">No image available</p>');
                                }
                                $firstImage = is_array($images) ? $images[0] : $images;
                                return new \Illuminate\Support\HtmlString(
                                    '<img src="' . htmlspecialchars($firstImage) . '" alt="Bottle" style="width: 300px; height: 300px; object-fit: cover; border-radius: 8px; border: 2px solid #e5e7eb;">'
                                );
                            })
                            ->columnSpan(1),
                        Infolists\Components\Group::make([
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
                                }),
                        ])
                            ->columnSpan(1)
                            ->extraAttributes(['class' => 'space-y-4']),
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
                        Infolists\Components\TextEntry::make('minimums')
                            ->label('Minimums'),
                    ])
                    ->columns(2),
            ]);
    }
}

