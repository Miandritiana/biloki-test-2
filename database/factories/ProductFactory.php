<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
        return [
            'category_id' => \App\Models\Category::factory(),
            'name' => $name = $this->faker->words(3, true),
            'slug' => \Illuminate\Support\Str::slug($name),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'SKU' => $this->faker->unique()->bothify('PROD-####'),
            'description' => $this->faker->paragraph,
            'is_active' => true,
        ];
    }
}
