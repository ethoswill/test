<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedusaDatabaseSyncService
{
    protected $medusaConnection;

    public function __construct()
    {
        // Use Medusa's database connection
        $this->medusaConnection = 'medusa';
    }

    /**
     * Sync a single product to Medusa database
     */
    public function syncProduct(Product $product): array
    {
        try {
            // Check if product already exists
            $existingProduct = $this->findExistingProduct($product->id);
            
            if ($existingProduct) {
                return $this->updateProduct($product, $existingProduct);
            } else {
                return $this->createProduct($product);
            }
        } catch (\Exception $e) {
            Log::error('Failed to sync product to Medusa database', [
                'laravel_id' => $product->id,
                'product_name' => $product->name,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => "Failed to sync product '{$product->name}': " . $e->getMessage(),
                'medusa_id' => null,
                'laravel_id' => $product->id
            ];
        }
    }

    /**
     * Sync multiple products to Medusa database
     */
    public function syncProducts(Collection $products): array
    {
        $successCount = 0;
        $errorCount = 0;
        $totalCount = $products->count();
        $errors = [];

        foreach ($products as $product) {
            $result = $this->syncProduct($product);
            if ($result['success']) {
                $successCount++;
            } else {
                $errorCount++;
                $errors[] = $result['message'];
            }
        }

        return [
            'total' => $totalCount,
            'success' => $successCount,
            'errors' => $errorCount,
            'messages' => $errors,
        ];
    }

    /**
     * Sync all products to Medusa database
     */
    public function syncAllProducts(): array
    {
        $allProducts = Product::all();
        return $this->syncProducts($allProducts);
    }

    /**
     * Find existing product in Medusa database
     */
    protected function findExistingProduct(int $laravelId): ?array
    {
        try {
            $product = DB::connection($this->medusaConnection)
                ->table('product')
                ->where('metadata->laravel_id', $laravelId)
                ->first();

            return $product ? (array) $product : null;
        } catch (\Exception $e) {
            Log::warning('Failed to search for existing product in Medusa database', [
                'laravel_id' => $laravelId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Create new product in Medusa database
     */
    protected function createProduct(Product $product): array
    {
        try {
            $medusaId = 'prod_' . $product->id;
            $handle = $this->generateHandle($product->name);
            
            // Insert product
            $productId = DB::connection($this->medusaConnection)
                ->table('product')
                ->insertGetId([
                    'id' => $medusaId,
                    'title' => $product->name,
                    'subtitle' => null,
                    'description' => $product->description ?? 'No description provided.',
                    'handle' => $handle,
                    'is_giftcard' => false,
                    'discountable' => true,
                    'status' => $this->mapStatus($product->status),
                    'metadata' => json_encode([
                        'laravel_id' => $product->id,
                        'supplier_id' => $product->supplier_id,
                        'supplier_code' => $product->supplier_code,
                        'website_url' => $product->website_url,
                        'hs_code' => $product->hs_code,
                        'parent_product' => $product->parent_product,
                        'product_type' => $product->product_type,
                        'minimums' => $product->minimums,
                        'starting_from_price' => $product->starting_from_price,
                        'fabric' => $product->fabric,
                        'how_it_fits' => $product->how_it_fits,
                        'care_instructions' => $product->care_instructions,
                        'lead_times' => $product->lead_times,
                        'available_sizes' => $product->available_sizes,
                        'customization_methods' => $product->customization_methods,
                        'model_size' => $product->model_size,
                        'ethos_cost_price' => $product->ethos_cost_price,
                        'customer_b2b_price' => $product->customer_b2b_price,
                        'customer_dtc_price' => $product->customer_dtc_price,
                        'franchisee_price' => $product->franchisee_price,
                        'minimum_order_quantity' => $product->minimum_order_quantity,
                        'split_across_variants' => $product->split_across_variants,
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            // Create default variant
            $this->createDefaultVariant($medusaId, $product);
            
            // Associate product with default sales channel
            $this->associateWithSalesChannel($medusaId);

            Log::info('Product created in Medusa database', [
                'laravel_id' => $product->id,
                'medusa_id' => $medusaId,
                'product_name' => $product->name
            ]);

            return [
                'success' => true,
                'message' => "Product '{$product->name}' created successfully",
                'medusa_id' => $medusaId,
                'laravel_id' => $product->id,
                'action' => 'created'
            ];
        } catch (\Exception $e) {
            throw new \Exception("Failed to create product: " . $e->getMessage());
        }
    }

    /**
     * Update existing product in Medusa database
     */
    protected function updateProduct(Product $product, array $existingProduct): array
    {
        try {
            $medusaId = $existingProduct['id'];
            $handle = $this->generateHandle($product->name);
            
            // Update product
            DB::connection($this->medusaConnection)
                ->table('product')
                ->where('id', $medusaId)
                ->update([
                    'title' => $product->name,
                    'description' => $product->description ?? 'No description provided.',
                    'handle' => $handle,
                    'status' => $this->mapStatus($product->status),
                    'metadata' => json_encode([
                        'laravel_id' => $product->id,
                        'supplier_id' => $product->supplier_id,
                        'supplier_code' => $product->supplier_code,
                        'website_url' => $product->website_url,
                        'hs_code' => $product->hs_code,
                        'parent_product' => $product->parent_product,
                        'product_type' => $product->product_type,
                        'minimums' => $product->minimums,
                        'starting_from_price' => $product->starting_from_price,
                        'fabric' => $product->fabric,
                        'how_it_fits' => $product->how_it_fits,
                        'care_instructions' => $product->care_instructions,
                        'lead_times' => $product->lead_times,
                        'available_sizes' => $product->available_sizes,
                        'customization_methods' => $product->customization_methods,
                        'model_size' => $product->model_size,
                        'ethos_cost_price' => $product->ethos_cost_price,
                        'customer_b2b_price' => $product->customer_b2b_price,
                        'customer_dtc_price' => $product->customer_dtc_price,
                        'franchisee_price' => $product->franchisee_price,
                        'minimum_order_quantity' => $product->minimum_order_quantity,
                        'split_across_variants' => $product->split_across_variants,
                    ]),
                    'updated_at' => now(),
                ]);

            Log::info('Product updated in Medusa database', [
                'laravel_id' => $product->id,
                'medusa_id' => $medusaId,
                'product_name' => $product->name
            ]);

            return [
                'success' => true,
                'message' => "Product '{$product->name}' updated successfully",
                'medusa_id' => $medusaId,
                'laravel_id' => $product->id,
                'action' => 'updated'
            ];
        } catch (\Exception $e) {
            throw new \Exception("Failed to update product: " . $e->getMessage());
        }
    }

    /**
     * Create default variant for product
     */
    protected function createDefaultVariant(string $productId, Product $product): void
    {
        try {
            $variantId = 'variant_' . $product->id;
            
            DB::connection($this->medusaConnection)
                ->table('product_variant')
                ->insert([
                    'id' => $variantId,
                    'title' => 'Default Variant',
                    'sku' => $product->supplier_code . '-DEFAULT',
                    'barcode' => null,
                    'ean' => null,
                    'upc' => null,
                    'allow_backorder' => false,
                    'manage_inventory' => true,
                    'hs_code' => null,
                    'origin_country' => null,
                    'mid_code' => null,
                    'material' => null,
                    'weight' => null,
                    'length' => null,
                    'height' => null,
                    'width' => null,
                    'metadata' => json_encode([]),
                    'variant_rank' => 0,
                    'product_id' => $productId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            // Create price for variant
            $this->createVariantPrice($variantId, $product);
            
        } catch (\Exception $e) {
            Log::warning('Failed to create default variant', [
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create price for variant
     */
    protected function createVariantPrice(string $variantId, Product $product): void
    {
        try {
            $priceId = 'price_' . $product->id;
            $priceSetId = 'pset_' . $product->id;
            
            // Create price set
            DB::connection($this->medusaConnection)
                ->table('price_set')
                ->insert([
                    'id' => $priceSetId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            // Create price
            DB::connection($this->medusaConnection)
                ->table('price')
                ->insert([
                    'id' => $priceId,
                    'title' => 'Default Price',
                    'price_set_id' => $priceSetId,
                    'currency_code' => 'usd',
                    'amount' => $product->ethos_cost_price,
                    'raw_amount' => json_encode([
                        'amount' => (int) ($product->ethos_cost_price * 100),
                        'currency_code' => 'usd'
                    ]),
                    'rules_count' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            // Link price set to variant
            DB::connection($this->medusaConnection)
                ->table('product_variant_price_set')
                ->insert([
                    'id' => 'pvps_' . $product->id,
                    'variant_id' => $variantId,
                    'price_set_id' => $priceSetId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
        } catch (\Exception $e) {
            Log::warning('Failed to create variant price', [
                'variant_id' => $variantId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Associate product with default sales channel
     */
    protected function associateWithSalesChannel(string $productId): void
    {
        try {
            // Get the default sales channel
            $salesChannel = DB::connection($this->medusaConnection)
                ->table('sales_channel')
                ->where('is_disabled', false)
                ->first();
            
            if ($salesChannel) {
                // Check if association already exists
                $existing = DB::connection($this->medusaConnection)
                    ->table('product_sales_channel')
                    ->where('product_id', $productId)
                    ->where('sales_channel_id', $salesChannel->id)
                    ->exists();
                
                if (!$existing) {
                    DB::connection($this->medusaConnection)
                        ->table('product_sales_channel')
                        ->insert([
                            'id' => 'psc_' . $productId,
                            'product_id' => $productId,
                            'sales_channel_id' => $salesChannel->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to associate product with sales channel', [
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate a URL-friendly handle from a product name
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
        $statusMapping = [
            'Active' => 'published',
            'Draft' => 'draft',
            'Supplier Product' => 'draft',
        ];
        
        return $statusMapping[$status] ?? 'draft';
    }
}
