<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Stock;
use Livewire\Attributes\Title;

new #[Title('Stock Management')] class extends Component {
    public ?Product $selectedProduct = null;
    public int $adjustmentAmount = 0;
    public string $adjustmentType = 'add'; // 'add' or 'remove'

    public function with(): array
    {
        return [
            'products' => Product::with(['category', 'stock'])->get(),
        ];
    }

    public function selectProduct(Product $product)
    {
        $this->selectedProduct = $product->load('stock');
        $this->adjustmentAmount = 0;
        $this->adjustmentType = 'add';
    }

    public function adjustStock()
    {
        $this->validate([
            'adjustmentAmount' => 'required|integer|min:1',
            'adjustmentType' => 'required|in:add,remove',
        ]);

        $stock = $this->selectedProduct->stock;
        
        if ($this->adjustmentType === 'add') {
            $stock->increment('quantity', $this->adjustmentAmount);
        } else {
            $newQuantity = max(0, $stock->quantity - $this->adjustmentAmount);
            $stock->update(['quantity' => $newQuantity]);
        }

        $this->selectedProduct = null;
    }
} ?>

<div class="space-y-6">
    <flux:heading size="xl">Stock Management Overview</flux:heading>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Product</flux:table.column>
                <flux:table.column>SKU</flux:table.column>
                <flux:table.column>Stock</flux:table.column>
                <flux:table.column>Threshold</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($products as $product)
                    <flux:table.row :key="$product->id">
                        <flux:table.cell class="font-medium text-zinc-900 dark:text-white">{{ $product->name }}</flux:table.cell>
                        <flux:table.cell>{{ $product->SKU }}</flux:table.cell>
                        <flux:table.cell class="font-mono">{{ $product->stock->quantity ?? 0 }}</flux:table.cell>
                        <flux:table.cell class="text-zinc-500">{{ $product->stock->alert_threshold ?? 10 }}</flux:table.cell>
                        <flux:table.cell>
                             @php
                                $stock = $product->stock ? $product->stock->quantity : 0;
                                $threshold = $product->stock ? $product->stock->alert_threshold : 0;
                            @endphp

                            @if ($stock <= 0)
                                <flux:badge color="red" size="sm">Out of Stock</flux:badge>
                            @elseif ($stock <= $threshold)
                                <flux:badge color="orange" size="sm">Low Stock</flux:badge>
                            @else
                                <flux:badge color="green" size="sm">Healthy</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:modal.trigger name="adjust-stock-modal">
                                <flux:button wire:click="selectProduct({{ $product->id }})" size="sm" variant="outline">Adjust</flux:button>
                            </flux:modal.trigger>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="adjust-stock-modal" class="md:w-96 space-y-6">
        @if($selectedProduct)
            <div>
                <flux:heading size="lg">Adjust Stock</flux:heading>
                <flux:description>{{ $selectedProduct->name }} (Current: {{ $selectedProduct->stock->quantity }})</flux:description>
            </div>

            <div class="space-y-4">
                <flux:radio.group wire:model="adjustmentType" label="Adjustment Type" variant="segmented">
                    <flux:radio value="add" label="Add Quantity" />
                    <flux:radio value="remove" label="Remove Quantity" />
                </flux:radio.group>

                <flux:input wire:model="adjustmentAmount" label="Quantity" type="number" min="1" required />
            </div>

            <div class="flex gap-2 justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="adjustStock" variant="primary">Confirm Adjustment</flux:button>
            </div>
        @endif
    </flux:modal>
</div>
