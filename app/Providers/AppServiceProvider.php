<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add custom CSS to Filament admin panel
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): View => view('filament.custom-css')
        );
    }
}