<?php

namespace App\Filament\Pages;

use App\Filament\Resources\DtfInHousePrintResource\Widgets\DtfSourcing;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\HexCodeColors;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\FileTypes;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\LeadTimesMinimums;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\CareInstructions;
use Filament\Pages\Page;

class ManageDtfInHousePrints extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationLabel = 'Direct To Film';
    
    protected static ?string $navigationGroup = 'In House Print';
    
    protected static ?int $navigationSort = 0;
    
    protected static string $view = 'filament.pages.manage-dtf-in-house-prints';

    public function getWidgets(): array
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

