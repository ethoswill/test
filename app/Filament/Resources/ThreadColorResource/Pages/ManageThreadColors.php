<?php

namespace App\Filament\Resources\ThreadColorResource\Pages;

use App\Filament\Resources\ThreadColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;

class ManageThreadColors extends ManageRecords
{
    protected static string $resource = ThreadColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadThreadColors')
                ->label('Download Thread Colors')
                ->icon('heroicon-o-arrow-down-tray')
                ->url('https://drive.google.com/uc?export=download&id=1tC7YLVxove4U8sY589lJzf3jqzYXGIsh')
                ->openUrlInNewTab()
                ->tooltip('The colors on the downloaded document might not 100% match the colors that the thread will be in person. For accurate thread colors check in person or download our Domestic Embroidery Thread Color Swatches'),
            Action::make('downloadDomesticSwatches')
                ->label('Download Domestic Embroidery Swatches')
                ->icon('heroicon-o-squares-2x2')
                ->url('#')
                ->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ];
    }
}

