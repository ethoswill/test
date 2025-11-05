<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Route;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->topNavigation()
            ->brandName('Ethos')
            ->brandLogo(asset('images/ethos-logo.svg'))
            ->favicon(asset('images/ethos-logo.svg'))
            ->colors([
                'primary' => Color::Blue,
            ])
            ->navigationGroups([
                NavigationGroup::make('Design Tools'),
                NavigationGroup::make('In House Print'),
                NavigationGroup::make('Embroidery'),
                NavigationGroup::make('Socks'),
                NavigationGroup::make('Bottles'),
                NavigationGroup::make('Towels'),
                NavigationGroup::make('Hair Clips'),
                NavigationGroup::make('Customer Service'),
                NavigationGroup::make('Files'),
                NavigationGroup::make('Administration'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages([
                \App\Filament\Pages\CustomDashboard::class,
                \App\Filament\Pages\ProfileSettings::class,
                \App\Filament\Pages\ManageDtfInHousePrints::class,
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->url(fn (): string => \App\Filament\Pages\ProfileSettings::getUrl())
                    ->icon('heroicon-o-user-circle'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->routes(function () {
                Route::get('/products/download-csv-template', function () {
                    $headers = [
                        'name',
                        'sku',
                        'supplier',
                        'product_type',
                        'website_url',
                        'base_color',
                        'tone_on_tone_darker',
                        'tone_on_tone_lighter',
                        'notes',
                        'fabric',
                        'available_sizes',
                        'price',
                        'cost',
                        'stock_quantity',
                        'min_stock_level',
                        'status',
                        'description',
                        'category',
                        'brand',
                        'weight',
                        'dimensions',
                        'barcode',
                        'is_featured',
                        'hs_code',
                        'parent_product',
                        'care_instructions',
                        'lead_times',
                        'customization_methods',
                        'model_size',
                        'starting_from_price',
                        'minimums',
                        'has_variants',
                        'cad_download',
                    ];
                    
                    // Create CSV content with headers
                    $csvContent = implode(',', $headers) . "\n";
                    
                    // Add sample row with example data
                    $sampleRow = [
                        'Sample Product',
                        'SKU-12345',
                        'Sample Supplier',
                        'T-Shirt',
                        'https://example.com/product',
                        '#ffffff',
                        '#e8e8e8',
                        '#f7f7f7',
                        'Sample notes',
                        'Cotton',
                        'S,M,L,XL',
                        '29.99',
                        '15.00',
                        '100',
                        '10',
                        'active',
                        'Product description',
                        'Apparel',
                        'Brand Name',
                        '0.5',
                        '10x12x2',
                        '123456789',
                        'false',
                        '',
                        '',
                        'Machine wash',
                        '2-3 weeks',
                        '',
                        'M',
                        '29.99',
                        '',
                        'false',
                        '',
                    ];
                    $csvContent .= implode(',', array_map(function($value) {
                        // Escape commas and quotes in CSV
                        if (strpos($value, ',') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
                            return '"' . str_replace('"', '""', $value) . '"';
                        }
                        return $value;
                    }, $sampleRow)) . "\n";
                    
                    return \Illuminate\Support\Facades\Response::streamDownload(function () use ($csvContent) {
                        echo $csvContent;
                    }, 'product_template.csv', [
                        'Content-Type' => 'text/csv; charset=UTF-8',
                    ]);
                })->name('resources.products.download-csv-template');
            });
    }
}
