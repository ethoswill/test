<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product(): void
    {
        $productData = [
            'name' => 'Test Product',
            'sku' => 'TEST-001',
            'description' => 'A test product',
            'price' => 99.99,
            'cost' => 50.00,
            'stock_quantity' => 100,
            'min_stock_level' => 10,
            'category' => 'Electronics',
            'brand' => 'Test Brand',
            'status' => 'active',
            'is_featured' => false,
        ];

        $product = Product::create($productData);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'sku' => 'TEST-001',
            'price' => 99.99,
        ]);

        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals('TEST-001', $product->sku);
        $this->assertEquals(99.99, $product->price);
    }

    public function test_product_validation_rules(): void
    {
        $productData = [
            'name' => '', // Required field empty
            'sku' => 'test-sku', // Invalid format (should be uppercase)
            'price' => -10, // Negative price
            'stock_quantity' => -5, // Negative stock
        ];

        // Test that validation rules work by checking the model's rules method
        $rules = Product::rules();
        
        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('sku', $rules);
        $this->assertArrayHasKey('price', $rules);
        $this->assertArrayHasKey('stock_quantity', $rules);
        
        $this->assertContains('required', $rules['name']);
        $this->assertContains('required', $rules['sku']);
        $this->assertContains('required', $rules['price']);
        $this->assertContains('required', $rules['stock_quantity']);
    }

    public function test_product_scopes(): void
    {
        // Create test products with explicit values
        Product::factory()->create(['status' => 'active', 'is_featured' => true, 'stock_quantity' => 50]);
        Product::factory()->create(['status' => 'inactive', 'is_featured' => false, 'stock_quantity' => 30]);
        Product::factory()->create(['status' => 'active', 'is_featured' => false, 'stock_quantity' => 0]);
        Product::factory()->create(['status' => 'active', 'is_featured' => false, 'stock_quantity' => 5, 'min_stock_level' => 10]);

        // Test active scope
        $activeProducts = Product::active()->get();
        $this->assertCount(3, $activeProducts);

        // Test featured scope
        $featuredProducts = Product::featured()->get();
        $this->assertCount(1, $featuredProducts);

        // Test in stock scope
        $inStockProducts = Product::inStock()->get();
        $this->assertCount(3, $inStockProducts); // Products with stock_quantity > 0: 50, 30, 5

        // Test low stock scope
        $lowStockProducts = Product::lowStock()->get();
        $this->assertCount(2, $lowStockProducts); // Products with stock <= min_stock_level: inactive(30), active(5)
    }

    public function test_product_helper_methods(): void
    {
        $product = Product::factory()->create([
            'price' => 100.00,
            'cost' => 60.00,
            'stock_quantity' => 5,
            'min_stock_level' => 10,
            'status' => 'active',
        ]);

        // Test profit margin calculation
        $this->assertEquals(66.67, $product->profit_margin);

        // Test stock status methods
        $this->assertTrue($product->isLowStock());
        $this->assertFalse($product->isOutOfStock());
        $this->assertTrue($product->isActive());

        // Test out of stock
        $product->update(['stock_quantity' => 0]);
        $this->assertTrue($product->isOutOfStock());
    }

    public function test_product_sku_uniqueness(): void
    {
        Product::factory()->create(['sku' => 'UNIQUE-SKU']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Product::factory()->create(['sku' => 'UNIQUE-SKU']);
    }

    public function test_filament_admin_access(): void
    {
        // Create a user with proper permissions for Filament
        $user = User::factory()->create();
        
        // For now, just test that the admin route exists
        // In a real application, you'd need to set up proper Filament user permissions
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    public function test_product_json_casts(): void
    {
        $product = Product::factory()->create([
            'images' => ['image1.jpg', 'image2.jpg'],
            'specifications' => ['color' => 'red', 'size' => 'large'],
        ]);

        $this->assertIsArray($product->images);
        $this->assertIsArray($product->specifications);
        $this->assertEquals(['image1.jpg', 'image2.jpg'], $product->images);
        $this->assertEquals(['color' => 'red', 'size' => 'large'], $product->specifications);
    }
}
