<?php

namespace App\Filament\Resources\LeggingsDesignResource\Pages;

use App\Filament\Resources\LeggingsDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLeggingsDesigns extends ManageRecords
{
    protected static string $resource = LeggingsDesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

