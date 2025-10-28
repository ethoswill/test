<?php

namespace App\Filament\Resources\HowToResource\Pages;

use App\Filament\Resources\HowToResource;
use App\Models\HowTo;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageHowTos extends ManageRecords
{
    protected static string $resource = HowToResource::class;

    protected static string $view = 'filament.resources.how-to-resource.pages.manage-how-tos';
    
    public function getHowTos()
    {
        return HowTo::ordered()->get();
    }
}
