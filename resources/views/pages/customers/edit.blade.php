<?php

use Livewire\Volt\Component;
use App\Models\Customer;
use Livewire\Attributes\Title;

new #[Title('Edit Customer')] class extends Component {
    public Customer $customer;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $city = '';
    public string $country = '';
    public bool $is_active = true;

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone ?? '';
        $this->address = $customer->address ?? '';
        $this->city = $customer->city ?? '';
        $this->country = $customer->country ?? '';
        $this->is_active = $customer->is_active;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $this->customer->id,
        ]);

        $this->customer->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'is_active' => $this->is_active,
        ]);

        return $this->redirect('/customers', navigate: true);
    }
} ?>

<div class="max-w-2xl mx-auto space-y-6">
    <flux:heading size="xl">Edit Customer: {{ $customer->name }}</flux:heading>

    <flux:card>
        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="name" label="Full Name" required />
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="email" label="Email Address" type="email" required />
                <flux:input wire:model="phone" label="Phone Number" />
            </div>

            <flux:textarea wire:model="address" label="Street Address" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="city" label="City" />
                <flux:input wire:model="country" label="Country" />
            </div>

            <flux:field>
                <flux:label>Active Status</flux:label>
                <flux:switch wire:model="is_active" />
            </flux:field>

            <div class="flex gap-2 justify-end">
                <flux:button href="{{ route('customers.index') }}" variant="ghost" wire:navigate>Cancel</flux:button>
                <flux:button type="submit" variant="primary">Update Customer</flux:button>
            </div>
        </form>
    </flux:card>
</div>
