<?php

namespace App\Filament\Resources\PackagingResource\Pages;

use App\Filament\Resources\PackagingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPackaging extends ViewRecord
{
    protected static string $resource = PackagingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [];
    }
}

