<?php

namespace App\Filament\Pages;

use App\Filament\Resources\DtfInHousePrintResource\Widgets\DtfSourcing;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\HexCodeColors;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\FileTypes;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\ContactInfo;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\IccProfiling;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\CareOfDtfGarment;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\LeadTimesMinimums;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\CareInstructions;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\PressSettings;
use App\Filament\Resources\DtfInHousePrintResource\Widgets\ToneOnToneColors;
use Filament\Actions\Action;
use Filament\Pages\Page;

class ManageDtfInHousePrints extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationLabel = 'Direct To Film';
    
    protected static ?string $navigationGroup = 'In House Print';
    
    protected static ?int $navigationSort = 0;
    
    protected static string $view = 'filament.pages.manage-dtf-in-house-prints';
    
    protected static string $routePath = 'dtf-in-house-prints';
    
    protected static ?string $title = 'DTF In House Prints';

    public function getWidgets(): array
    {
        return [
            FileTypes::class,
            ContactInfo::class,
            IccProfiling::class,
            CareOfDtfGarment::class,
            PressSettings::class,
            ToneOnToneColors::class,
        ];
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('orderDtfLogo')
                ->label('Order DTF Logo')
                ->icon('heroicon-o-shopping-cart')
                ->color('success')
                ->url('https://secure.supacolor.com/secure/quickjob/quickjob.aspx')
                ->openUrlInNewTab(),
            Action::make('artworkSpecifications')
                ->label('Artwork Specifications')
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->url('https://drive.google.com/file/d/1CNe0oBKVDd5cFUYo8mGi7l7hEwgoe7pr/view')
                ->openUrlInNewTab(),
        ];
    }
}

