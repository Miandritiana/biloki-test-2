<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    \Livewire\Volt\Volt::route('dashboard', 'dashboard')->name('dashboard');
    \Livewire\Volt\Volt::route('products', 'products.index')->name('products.index');
    \Livewire\Volt\Volt::route('products/create', 'products.create')->name('products.create');
    \Livewire\Volt\Volt::route('products/{product}/edit', 'products.edit')->name('products.edit');
    \Livewire\Volt\Volt::route('customers', 'customers.index')->name('customers.index');
    \Livewire\Volt\Volt::route('customers/create', 'customers.create')->name('customers.create');
    \Livewire\Volt\Volt::route('customers/{customer}/edit', 'customers.edit')->name('customers.edit');
    \Livewire\Volt\Volt::route('stock', 'stock.index')->name('stock.index');
});

require __DIR__.'/settings.php';
