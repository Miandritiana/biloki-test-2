<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;

new #[Title('Create Product')] class extends Component {
    public string $name = '';
    public string $category_id = '';
    public string $price = '';
    public string $SKU = '';
    public string $description = '';
    public string $image = '';
    public int $quantity = 0;
    public int $alert_threshold = 10;

    public function with(): array
    {
        return [
            'categories' => Category::all(),
        ];
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'SKU' => 'required|string|unique:products,SKU',
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::create([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'price' => $this->price,
            'SKU' => $this->SKU,
            'description' => $this->description,
            'image' => $this->image,
        ]);

        Stock::create([
            'product_id' => $product->id,
            'quantity' => $this->quantity,
            'alert_threshold' => $this->alert_threshold,
        ]);

        return $this->redirect('/products', navigate: true);
    }
} ?>

<div class="max-w-2xl mx-auto space-y-6">
    <flux:heading size="xl">Add New Product</flux:heading>

    <flux:card>
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="name" label="Product Name" placeholder="e.g. iPhone 15" required />
                <flux:select wire:model="category_id" label="Category" placeholder="Select category..." required>
                    @foreach($categories as $category)
                        <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="price" label="Price (€)" type="number" step="0.01" required />
                <flux:input wire:model="SKU" label="SKU" placeholder="e.g. PHN-001" required />
            </div>

            <flux:textarea wire:model="description" label="Description" placeholder="Product details..." />

            <flux:separator />

            <flux:heading size="md">Initial Stock Levels</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="quantity" label="Current Quantity" type="number" required />
                <flux:input wire:model="alert_threshold" label="Alert Threshold" type="number" required />
            </div>

            <div class="flex gap-2 justify-end">
                <flux:button href="{{ route('products.index') }}" variant="ghost" wire:navigate>Cancel</flux:button>
                <flux:button type="submit" variant="primary">Create Product</flux:button>
            </div>
        </form>
    </flux:card>
</div>
