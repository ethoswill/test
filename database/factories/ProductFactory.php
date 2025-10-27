<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Electronics', 'Clothing', 'Books', 'Home & Garden', 'Sports', 'Toys'];
        $brands = ['Apple', 'Samsung', 'Nike', 'Adidas', 'Sony', 'Microsoft'];
        $statuses = ['active', 'inactive', 'discontinued'];

        return [
            'name' => $this->faker->words(3, true),
            'sku' => strtoupper($this->faker->unique()->bothify('???-###-???')),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'cost' => $this->faker->randomFloat(2, 5, 500),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'min_stock_level' => $this->faker->numberBetween(1, 20),
            'category' => $this->faker->randomElement($categories),
            'brand' => $this->faker->randomElement($brands),
            'status' => $this->faker->randomElement($statuses),
            'images' => $this->faker->optional()->randomElements([
                'product1.jpg', 'product2.jpg', 'product3.jpg'
            ], $this->faker->numberBetween(1, 3)),
            'specifications' => [
                'color' => $this->faker->colorName(),
                'material' => $this->faker->word(),
                'size' => $this->faker->randomElement(['Small', 'Medium', 'Large', 'XL']),
            ],
            'weight' => $this->faker->randomFloat(2, 0.1, 50),
            'dimensions' => $this->faker->randomElement(['10x20x30 cm', '15x25x35 cm', '20x30x40 cm']),
            'barcode' => $this->faker->ean13(),
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
