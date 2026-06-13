<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Livewire\Volt\Volt;

test('authenticated user can view products', function () {
    $user = User::factory()->create();
    Category::factory()->create();
    
    $this->actingAs($user)
        ->get('/products')
        ->assertStatus(200);
});

test('products can be deleted', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    
    $this->actingAs($user);
    
    Volt::test('products.index')
        ->call('delete', $product->id)
        ->assertStatus(200);
        
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});
