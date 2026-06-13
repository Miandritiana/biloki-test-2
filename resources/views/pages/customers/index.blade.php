<?php

use Livewire\Volt\Component;
use App\Models\Customer;
use Livewire\Attributes\Title;

new #[Title('Customers')] class extends Component {
    public function with(): array
    {
        return [
            'customers' => Customer::latest()->get(),
        ];
    }

    public function toggleActive(Customer $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);
    }

    public function delete(Customer $customer)
    {
        $customer->delete();
    }
} ?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <flux:heading size="xl">Customer Management</flux:heading>
        <flux:button href="{{ route('customers.create') }}" variant="primary" icon="plus" wire:navigate>Add Customer</flux:button>
    </div>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Contact</flux:table.column>
                <flux:table.column>Location</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($customers as $customer)
                    <flux:table.row :key="$customer->id">
                        <flux:table.cell class="font-medium text-zinc-900 dark:text-white">{{ $customer->name }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="text-sm">{{ $customer->email }}</div>
                            <div class="text-xs text-zinc-500">{{ $customer->phone }}</div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="text-sm">{{ $customer->city }}</div>
                            <div class="text-xs text-zinc-500">{{ $customer->country }}</div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:switch wire:click="toggleActive({{ $customer->id }})" :checked="$customer->is_active" />
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2">
                                <flux:button icon="pencil-square" size="sm" variant="ghost" href="{{ route('customers.edit', $customer) }}" wire:navigate />
                                <flux:button icon="trash" size="sm" variant="ghost" color="red" wire:click="delete({{ $customer->id }})" wire:confirm="Delete this customer?" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
