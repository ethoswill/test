<?php

namespace App\Filament\Resources\PuffPrintColorResource\Widgets;

use Filament\Widgets\Widget;

class PuffPrintTypicalLocations extends Widget
{
    protected static string $view = 'filament.resources.puff-print-color-resource.widgets.puff-print-typical-locations';

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected static ?int $sort = 3;
}
