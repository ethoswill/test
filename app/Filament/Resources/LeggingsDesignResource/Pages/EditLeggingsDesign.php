<?php

namespace App\Filament\Resources\LeggingsDesignResource\Pages;

use App\Filament\Resources\LeggingsDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeggingsDesign extends EditRecord
{
    protected static string $resource = LeggingsDesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

