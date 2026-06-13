<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;

new #[Title('Products')] class extends Component {
    public function with(): array
    {
        return [
            'products' => Product::with(['category', 'stock'])->latest()->get(),
        ];
    }

    public function delete(Product $product)
    {
        $product->delete();
    }
} ?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <flux:heading size="xl">Products Management</flux:heading>
        <flux:button href="{{ route('products.create') }}" variant="primary" icon="plus" wire:navigate>Add Product</flux:button>
    </div>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Product</flux:table.column>
                <flux:table.column>Category</flux:table.column>
                <flux:table.column>SKU</flux:table.column>
                <flux:table.column>Price</flux:table.column>
                <flux:table.column>Stock</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($products as $product)
                    <flux:table.row :key="$product->id">
                        <flux:table.cell class="font-medium">
                            <div class="flex items-center gap-3">
                                @if($product->image)
                                    <img src="{{ $product->image }}" class="size-8 rounded-lg object-cover">
                                @endif
                                <div>
                                    <div class="font-semibold">{{ $product->name }}</div>
                                    <div class="text-xs text-zinc-500">{{ Str::limit($product->description, 50) }}</div>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>{{ $product->category->name }}</flux:table.cell>
                        <flux:table.cell>{{ $product->SKU }}</flux:cell>
                        <flux:table.cell>{{ number_format($product->price, 2) }} €</flux:table.cell>
                        <flux:table.cell>{{ $product->stock->quantity ?? 0 }}</flux:table.cell>
                        <flux:table.cell>
                            @php
                                $stock = $product->stock ? $product->stock->quantity : 0;
                                $threshold = $product->stock ? $product->stock->alert_threshold : 0;
                            @endphp

                            @if ($stock <= 0)
                                <flux:badge color="red" inset="top bottom">Out of Stock</flux:badge>
                            @elseif ($stock <= $threshold)
                                <flux:badge color="orange" inset="top bottom">Low Stock</flux:badge>
                            @else
                                <flux:badge color="green" inset="top bottom">In Stock</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2">
                                <flux:button icon="pencil-square" size="sm" variant="ghost" href="{{ route('products.edit', $product) }}" wire:navigate />
                                <flux:button icon="trash" size="sm" variant="ghost" color="red" wire:click="delete({{ $product->id }})" wire:confirm="Are you sure you want to delete this product?" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
