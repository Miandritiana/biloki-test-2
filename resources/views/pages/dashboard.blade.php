<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Stock;
use Livewire\Attributes\Title;

new #[Title('Dashboard')] class extends Component {
    public function with(): array
    {
        return [
            'totalProducts' => Product::count(),
            'lowStockAlerts' => Stock::whereColumn('quantity', '<=', 'alert_threshold')->count(),
            'activeCustomers' => Customer::where('is_active', true)->count(),
        ];
    }
} ?>

<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <flux:card class="space-y-2">
            <flux:heading size="lg">Total Products</flux:heading>
            <flux:text size="xl" class="font-bold">{{ $totalProducts }}</flux:text>
            <flux:button href="/products" variant="ghost" size="sm" wire:navigate>View Products</flux:button>
        </flux:card>

        <flux:card class="space-y-2 border-orange-200 bg-orange-50 dark:bg-orange-900/10">
            <flux:heading size="lg" class="text-orange-700 dark:text-orange-400">Low Stock Alerts</flux:heading>
            <flux:text size="xl" class="font-bold text-orange-800 dark:text-orange-300">{{ $lowStockAlerts }}</flux:text>
            <flux:button href="/stock" variant="ghost" size="sm" wire:navigate>Manage Stock</flux:button>
        </flux:card>

        <flux:card class="space-y-2">
            <flux:heading size="lg">Active Customers</flux:heading>
            <flux:text size="xl" class="font-bold">{{ $activeCustomers }}</flux:text>
            <flux:button href="/customers" variant="ghost" size="sm" wire:navigate>View Customers</flux:button>
        </flux:card>
    </div>
</div>
