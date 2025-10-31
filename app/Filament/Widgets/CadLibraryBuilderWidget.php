<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\TableWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CadLibraryBuilderWidget extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static string $view = 'filament.widgets.cad-library-builder-widget';

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->whereNotNull('cad_download'))
            ->columns([
                TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->product_type)
                    ->color('primary'),
                TextColumn::make('base_color')
                    ->label('Base Color')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return 'N/A';
                        return new \Illuminate\Support\HtmlString(
                            '<div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 20px; height: 20px; background-color: ' . $state . '; border: 1px solid #ccc; border-radius: 3px;"></div>
                                <span>' . $state . '</span>
                            </div>'
                        );
                    })
                    ->html(),
                TextColumn::make('supplier')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
            ])
            ->selectable()
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('add_to_selection')
                        ->label('Add Selected to Builder')
                        ->icon('heroicon-o-plus')
                        ->color('primary')
                        ->action(function ($records) {
                            $existing = session('cad_selection', []);
                            $newIds = $records->pluck('id')->toArray();
                            $combined = array_unique(array_merge($existing, $newIds));
                            session(['cad_selection' => array_values($combined)]);
                            
                            // Force session save
                            session()->save();
                            
                            Notification::make()
                                ->title('Products Added')
                                ->body(count($newIds) . ' product(s) added to your CAD library builder. Total: ' . count($combined))
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->headerActions([
                \Filament\Tables\Actions\Action::make('view_selection')
                    ->label(fn () => 'View Selection (' . count(session('cad_selection', [])) . ')')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->action(function () {
                        $selectedIds = session('cad_selection', []);
                        if (empty($selectedIds)) {
                            Notification::make()
                                ->title('No Products Selected')
                                ->body('Select products from the table and click "Add Selected to Builder".')
                                ->info()
                                ->send();
                            return;
                        }
                        
                        $products = Product::whereIn('id', $selectedIds)->get();
                        $productList = $products->pluck('name')->join(', ');
                        
                        Notification::make()
                            ->title('Selected Products (' . count($selectedIds) . ')')
                            ->body($productList)
                            ->success()
                            ->send();
                    }),
                \Filament\Tables\Actions\Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function () {
                        return $this->downloadSelected();
                    }),
                \Filament\Tables\Actions\Action::make('clear_selection')
                    ->label('Clear Selection')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Clear Selection')
                    ->modalDescription('Are you sure you want to clear all selected products?')
                    ->action(function () {
                        $this->clearSelection();
                    }),
            ])
            ->defaultSort('name', 'asc')
            ->paginated([10, 25, 50, 100]);
    }

    public function downloadSelected()
    {
        $selectedIds = session('cad_selection', []);
        
        if (empty($selectedIds)) {
            Notification::make()
                ->title('No Selection')
                ->body('Please select products from the table above and click "Add Selected to Builder".')
                ->warning()
                ->send();
            return null;
        }

        // Get products in the order they were selected
        $products = collect($selectedIds)->map(function ($id) {
            return Product::find($id);
        })->filter();

        if ($products->isEmpty()) {
            Notification::make()
                ->title('No Products Found')
                ->body('The selected products could not be found.')
                ->warning()
                ->send();
            return null;
        }

        try {
            // Generate main PDF with product info
            $pdf = Pdf::loadView('filament.widgets.cad-library-builder-pdf', [
                'products' => $products,
                'selectedCount' => count($selectedIds),
            ])
                ->setPaper('a4', 'portrait')
                ->setOption('isRemoteEnabled', true);
            
            // Save main PDF to temp file
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $mainPdfPath = $tempDir . '/main-' . Str::random(10) . '.pdf';
            file_put_contents($mainPdfPath, $pdf->output());
            
            // Create merged PDF using FPDI
            $mergedPdf = new Fpdi();
            
            // Import main PDF
            $pageCount = $mergedPdf->setSourceFile($mainPdfPath);
            for ($i = 1; $i <= $pageCount; $i++) {
                $mergedPdf->AddPage();
                $tplId = $mergedPdf->importPage($i);
                $mergedPdf->useTemplate($tplId);
            }
            
            // Download and merge CAD PDFs
            foreach ($products as $product) {
                if (!$product->cad_download) {
                    continue;
                }
                
                $cadPath = $this->downloadCadFile($product->cad_download, $tempDir);
                if ($cadPath && file_exists($cadPath)) {
                    try {
                        $cadPageCount = $mergedPdf->setSourceFile($cadPath);
                        for ($i = 1; $i <= $cadPageCount; $i++) {
                            $mergedPdf->AddPage();
                            $tplId = $mergedPdf->importPage($i);
                            $mergedPdf->useTemplate($tplId);
                        }
                        // Clean up downloaded CAD file
                        @unlink($cadPath);
                    } catch (\Exception $e) {
                        // Skip if CAD PDF can't be merged
                        continue;
                    }
                }
            }
            
            // Clean up main PDF
            @unlink($mainPdfPath);
            
            $filename = 'cad-library-builder-' . date('Y-m-d-His') . '.pdf';
            
            return response()->streamDownload(function () use ($mergedPdf) {
                echo $mergedPdf->Output('', 'S');
            }, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
            
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Generating PDF')
                ->body('There was a problem creating the PDF: ' . $e->getMessage())
                ->danger()
                ->send();
            
            return null;
        }
    }
    
    private function downloadCadFile($cadUrl, $tempDir): ?string
    {
        try {
            // Check if it's a URL
            if (filter_var($cadUrl, FILTER_VALIDATE_URL)) {
                // Download from URL
                $response = Http::timeout(30)->get($cadUrl);
                if ($response->successful()) {
                    $content = $response->body();
                    $extension = pathinfo(parse_url($cadUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'pdf';
                    $tempPath = $tempDir . '/cad-' . Str::random(10) . '.' . $extension;
                    file_put_contents($tempPath, $content);
                    return $tempPath;
                }
            } else {
                // Local file path
                $paths = [
                    storage_path('app/public/' . $cadUrl),
                    public_path($cadUrl),
                    str_starts_with($cadUrl, '/') ? $cadUrl : null,
                ];
                
                foreach ($paths as $path) {
                    if ($path && file_exists($path) && is_file($path)) {
                        // Check if it's a PDF
                        $extension = pathinfo($path, PATHINFO_EXTENSION);
                        if (strtolower($extension) === 'pdf') {
                            return $path;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Failed to download/access CAD file
        }
        
        return null;
    }

    public function clearSelection(): void
    {
        session()->forget('cad_selection');
        session()->save();
        Notification::make()
            ->title('Selection Cleared')
            ->body('All products have been removed from the builder.')
            ->success()
            ->send();
    }
}

