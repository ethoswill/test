<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class CustomDashboard extends BaseDashboard
{
    public function getTitle(): string | Htmlable
    {
        $user = auth()->user();
        $firstName = $user->first_name ?? $user->name;
        return 'Hello ' . $firstName;
    }
}
