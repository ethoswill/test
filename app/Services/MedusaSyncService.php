<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class MedusaSyncService
{
    protected $baseUrl;
    protected $apiKey;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = config('medusa.base_url');
        $this->apiKey = config('medusa.api_key');
        $this->timeout = config('medusa.timeout');
    }

    /**
     * Sync a single product to Medusa
     */
    public function syncProduct(Product $product): array
    {
        try {
            // Use database sync instead of API sync
            $dbSyncService = new \App\Services\MedusaDatabaseSyncService();
            return $dbSyncService->syncProduct($product);
        } catch (\Exception $e) {
            Log::error('Failed to sync product', [
                'laravel_id' => $product->id,
                'product_name' => $product->name,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => "Failed to sync product '{$product->name}': " . $e->getMessage(),
                'laravel_id' => $product->id
            ];
        }
    }

    /**
     * Sync multiple products to Medusa
     */
    public function syncProducts(Collection $products): array
    {
        $results = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($products as $product) {
            $result = $this->syncProduct($product);
            $results[] = $result;
            
            if ($result['success']) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        return [
            'total' => $products->count(),
            'success' => $successCount,
            'errors' => $errorCount,
            'results' => $results
        ];
    }

    /**
     * Sync all products to Medusa
     */
    public function syncAllProducts(): array
    {
        $products = Product::all();
        return $this->syncProducts($products);
    }

    /**
     * Format Laravel product data for Medusa
     */
    protected function formatProductForMedusa(Product $product): array
    {
        return [
            'id' => $product->id,
            'title' => $product->name,
            'description' => $this->generateDescription($product),
            'handle' => $this->generateHandle($product->name),
            'status' => $this->mapStatus($product->status),
            'price' => $product->customer_dtc_price ?? $product->customer_b2b_price ?? 0,
            'currency' => 'USD',
            'weight' => 0.5, // Default weight
            'hs_code' => $product->hs_code,
            'origin_country' => 'US',
            'material' => $product->fabric,
            'variants' => $this->generateVariants($product),
            'metadata' => [
                'laravel_id' => $product->id,
                'supplier_id' => $product->supplier_id,
                'supplier_code' => $product->supplier_code,
                'store_id' => $product->store_id,
                'product_type' => $product->product_type,
                'customization_methods' => $product->customization_methods,
                'minimum_order_quantity' => $product->minimum_order_quantity,
                'split_across_variants' => $product->split_across_variants,
                'ethos_cost_price' => $product->ethos_cost_price,
                'customer_b2b_price' => $product->customer_b2b_price,
                'customer_dtc_price' => $product->customer_dtc_price,
                'franchisee_price' => $product->franchisee_price,
                'minimums' => $product->minimums,
                'starting_from_price' => $product->starting_from_price,
                'how_it_fits' => $product->how_it_fits,
                'care_instructions' => $product->care_instructions,
                'lead_times' => $product->lead_times,
                'available_sizes' => $product->available_sizes,
                'model_size' => $product->model_size,
            ]
        ];
    }

    /**
     * Generate product description
     */
    protected function generateDescription(Product $product): string
    {
        $description = "Product: {$product->name}";
        
        if ($product->fabric) {
            $description .= "\nFabric: {$product->fabric}";
        }
        
        if ($product->how_it_fits) {
            $description .= "\nFit: {$product->how_it_fits}";
        }
        
        if ($product->care_instructions) {
            $description .= "\nCare: {$product->care_instructions}";
        }
        
        if ($product->customization_methods) {
            $description .= "\nCustomization: {$product->customization_methods}";
        }
        
        return $description;
    }

    /**
     * Generate handle from product name
     */
    protected function generateHandle(string $name): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
    }

    /**
     * Map Laravel status to Medusa status
     */
    protected function mapStatus(string $status): string
    {
        $statusMapping = config('medusa.mapping.status', []);
        return $statusMapping[$status] ?? 'draft';
    }

    /**
     * Generate variants for the product
     */
    protected function generateVariants(Product $product): array
    {
        $variants = [];
        
        // Create a default variant
        $variant = [
            'title' => 'Default',
            'sku' => $product->supplier_code ?? 'PROD-' . $product->id,
            'price' => $product->customer_dtc_price ?? $product->customer_b2b_price ?? 0,
            'currency' => 'USD',
            'weight' => 0.5,
            'options' => [
                'Size' => $product->available_sizes ?? 'One Size',
                'Color' => 'Default'
            ]
        ];
        
        $variants[] = $variant;
        
        return $variants;
    }

    /**
     * Test connection to Medusa
     */
    public function testConnection(): array
    {
        try {
            // First try with API key if provided
            if (!empty($this->apiKey) && $this->apiKey !== 'your-api-key-here') {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->get("{$this->baseUrl}/admin/products");

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'message' => 'Connection to Medusa successful',
                        'status' => $response->status()
                    ];
                }
            }
            
            // If API key auth fails, try without authentication (for development)
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->get("{$this->baseUrl}/app");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connection to Medusa successful (development mode)',
                    'status' => $response->status()
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Connection failed: HTTP ' . $response->status(),
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
                'status' => 0
            ];
        }
    }

    /**
     * Find existing product in Medusa by Laravel ID
     */
    protected function findExistingProduct(int $laravelId): ?array
    {
        try {
            $headers = [
                'Content-Type' => 'application/json',
            ];
            
            // Add publishable API key header for store API
            if (!empty($this->apiKey) && $this->apiKey !== 'your-api-key-here') {
                $headers['x-publishable-api-key'] = $this->apiKey;
            } else {
                return null; // Can't search without API key
            }
            
            // Search for products with Laravel ID in metadata using store API
            $response = Http::timeout($this->timeout)
                ->withHeaders($headers)
                ->get("{$this->baseUrl}/store/products");
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['products']) && count($data['products']) > 0) {
                    // Search for product with matching Laravel ID in metadata
                    foreach ($data['products'] as $product) {
                        if (isset($product['metadata']['laravel_id']) && 
                            $product['metadata']['laravel_id'] == $laravelId) {
                            return $product;
                        }
                    }
                }
            }
            
            return null;
        } catch (\Exception $e) {
            Log::warning('Failed to search for existing product', [
                'laravel_id' => $laravelId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
