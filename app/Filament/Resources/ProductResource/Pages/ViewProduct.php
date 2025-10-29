<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('previous')
                ->label('Previous Product')
                ->icon('heroicon-o-chevron-left')
                ->color('gray')
                ->url(function () {
                    $previousProduct = \App\Models\Product::where('id', '<', $this->record->id)
                        ->orderBy('id', 'desc')
                        ->first();
                    
                    if ($previousProduct) {
                        return route('filament.admin.resources.products.view', $previousProduct);
                    }
                    
                    return null;
                })
                ->disabled(function () {
                    return !\App\Models\Product::where('id', '<', $this->record->id)->exists();
                }),
            Actions\Action::make('next')
                ->label('Next Product')
                ->icon('heroicon-o-chevron-right')
                ->color('gray')
                ->url(function () {
                    $nextProduct = \App\Models\Product::where('id', '>', $this->record->id)
                        ->orderBy('id', 'asc')
                        ->first();
                    
                    if ($nextProduct) {
                        return route('filament.admin.resources.products.view', $nextProduct);
                    }
                    
                    return null;
                })
                ->disabled(function () {
                    return !\App\Models\Product::where('id', '>', $this->record->id)->exists();
                }),
            Actions\EditAction::make(),
        ];
    }

    protected function getHeaderTitle(): string
    {
        return $this->record->name;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Product Information')
                ->schema([
                    Forms\Components\Placeholder::make('title')
                        ->label('Product Name')
                        ->content(fn ($record) => $record->name)
                        ->extraAttributes(['class' => 'text-2xl font-bold']),
                    Forms\Components\Placeholder::make('supplier')
                        ->label('Supplier')
                        ->content(fn ($record) => $record->supplier ?? 'N/A'),
                    Forms\Components\Placeholder::make('product_type')
                        ->label('Product Type')
                        ->content(fn ($record) => $record->product_type ?? 'N/A'),
                ])
                ->columns(1),

            Forms\Components\Section::make('Design Notes')
                ->schema([
                    Forms\Components\Placeholder::make('base_color')
                        ->label('Base Color (Illustrator)')
                        ->content(function ($record) {
                            $color = $record->base_color ?? 'N/A';
                            if ($color !== 'N/A' && $color) {
                                return new \Illuminate\Support\HtmlString(
                                    '<div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 30px; height: 30px; background-color: ' . htmlspecialchars($color) . '; border: 2px solid #ccc; border-radius: 4px;"></div>
                                        <span class="font-mono">' . htmlspecialchars($color) . '</span>
                                    </div>'
                                );
                            }
                            return 'N/A';
                        }),
                    Forms\Components\Placeholder::make('tone_on_tone_darker')
                        ->label('Tone on Tone (Darker)')
                        ->content(function ($record) {
                            $color = $record->tone_on_tone_darker ?? 'N/A';
                            if ($color !== 'N/A' && $color) {
                                return new \Illuminate\Support\HtmlString(
                                    '<div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 30px; height: 30px; background-color: ' . htmlspecialchars($color) . '; border: 2px solid #ccc; border-radius: 4px;"></div>
                                        <span class="font-mono">' . htmlspecialchars($color) . '</span>
                                    </div>'
                                );
                            }
                            return 'N/A';
                        }),
                    Forms\Components\Placeholder::make('tone_on_tone_lighter')
                        ->label('Tone on Tone (Lighter)')
                        ->content(function ($record) {
                            $color = $record->tone_on_tone_lighter ?? 'N/A';
                            if ($color !== 'N/A' && $color) {
                                return new \Illuminate\Support\HtmlString(
                                    '<div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 30px; height: 30px; background-color: ' . htmlspecialchars($color) . '; border: 2px solid #ccc; border-radius: 4px;"></div>
                                        <span class="font-mono">' . htmlspecialchars($color) . '</span>
                                    </div>'
                                );
                            }
                            return 'N/A';
                        }),
                    Forms\Components\Placeholder::make('cad_download')
                        ->label('CAD Download')
                        ->content(function ($record) {
                            if ($record->cad_download) {
                                return new \Illuminate\Support\HtmlString(
                                    '<a href="' . asset('storage/' . $record->cad_download) . '" download style="color: #3b82f6; text-decoration: underline;">Download CAD File</a>'
                                );
                            }
                            return 'No CAD file uploaded';
                        }),
                ])
                ->columns(1),

            Forms\Components\Section::make('Additional Information')
                ->schema([
                    Forms\Components\Placeholder::make('website_url')
                        ->label('Website URL')
                        ->content(fn ($record) => $record->website_url ? new \Illuminate\Support\HtmlString('<a href="' . htmlspecialchars($record->website_url) . '" target="_blank" style="color: #3b82f6; text-decoration: underline;">' . htmlspecialchars($record->website_url) . '</a>') : 'N/A'),
                    Forms\Components\Placeholder::make('notes')
                        ->label('Notes')
                        ->content(fn ($record) => $record->notes ?? 'No notes'),
                ])
                ->columns(1)
                ->collapsible(),
        ];
    }

    public function getFormActions(): array
    {
        return [];
    }
}
