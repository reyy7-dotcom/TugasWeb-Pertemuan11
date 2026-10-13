<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 9999),
            'description' => fake()->paragraphs(2, true),
            'price' => fake()->randomFloat(2, 50000, 25000000),
            'stock' => fake()->numberBetween(0, 150),
            'sku' => 'SKU-' . strtoupper(Str::random(8)),
            'image_url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80',
            'is_active' => fake()->boolean(90),
        ];
    }

    /**
     * State for active products.
     */
    public function active(): static
    {
        return $this->state(fn () => ['is_active' => true]);
    }

    /**
     * State for in stock products.
     */
    public function inStock(): static
    {
        return $this->state(fn () => ['stock' => fake()->numberBetween(10, 100), 'is_active' => true]);
    }

    /**
     * State for out of stock products.
     */
    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0]);
    }
}
