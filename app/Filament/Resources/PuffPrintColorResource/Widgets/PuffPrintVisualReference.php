<?php

namespace App\Filament\Resources\PuffPrintColorResource\Widgets;

use Filament\Widgets\Widget;

class PuffPrintVisualReference extends Widget
{
    protected static string $view = 'filament.resources.puff-print-color-resource.widgets.puff-print-visual-reference';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 10;
}

