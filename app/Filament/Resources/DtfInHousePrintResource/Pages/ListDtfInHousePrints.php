<?php

namespace App\Filament\Resources\DtfInHousePrintResource\Pages;

use App\Filament\Resources\DtfInHousePrintResource;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\DtfSourcing;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\HexCodeColors;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\FileTypes;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\LeadTimesMinimums;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\CareInstructions;
use Filament\Resources\Pages\ListRecords;

class ListDtfInHousePrints extends ListRecords
{
    protected static string $resource = DtfInHousePrintResource::class;

    protected static ?string $title = 'DTF In House Prints';

    public function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DtfSourcing::class,
            HexCodeColors::class,
            FileTypes::class,
            LeadTimesMinimums::class,
            CareInstructions::class,
        ];
    }
}
