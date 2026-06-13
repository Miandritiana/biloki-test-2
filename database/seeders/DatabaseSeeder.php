<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        \App\Models\Category::factory(5)->create()->each(function ($category) {
            \App\Models\Product::factory(4)->create([
                'category_id' => $category->id,
            ])->each(function ($product) {
                \App\Models\Stock::factory()->create([
                    'product_id' => $product->id,
                ]);
            });
        });

        \App\Models\Customer::factory(15)->create();
    }
}
