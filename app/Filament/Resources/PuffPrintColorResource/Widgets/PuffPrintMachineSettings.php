<?php

namespace App\Filament\Resources\PuffPrintColorResource\Widgets;

use Filament\Widgets\Widget;

class PuffPrintMachineSettings extends Widget
{
    protected static string $view = 'filament.resources.puff-print-color-resource.widgets.puff-print-machine-settings';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;
}

