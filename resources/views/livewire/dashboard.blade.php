<?php

use App\Models\User;
use Livewire\Volt\Component;
use Illuminate\View\View;
use Livewire\Attributes\{Layout, Title};
use Developermithu\Tallcraftui\Traits\WithTcTable;

new 
// #[Layout('components.layouts.app')]
#[Title('Dashboard')]
class extends Component {
    use WithTcTable;
    // public $listeners = ['refresh' => '$refresh'];
    public bool $is_active = false;
    public bool $is_admin = false;
    public bool $email_verified_at = false;

    public function with(): array
    {
        return [
            'users' => User::query()
                ->when($this->tcSearch, function ($query) {
                    $query->where('name', 'LIKE', '%' . $this->tcSearch . '%');
                })
                ->when($this->is_active, function ($query) {
                    $query->where('is_active', true);
                })
                ->when($this->is_admin, function ($query) {
                    $query->where('is_admin', true);
                })
                ->when($this->email_verified_at, function ($query) {
                    $query->whereNotNull('email_verified_at');
                })
                ->tap(fn($query) => $this->tcApplySorting($query))
                ->paginate($this->tcPerPage),
        ];
    }

    // public function getOrdersProperty()
    // {
    //     return User::latest()->take(5)->get();
    // }
    // public function getTotalUsersProperty()
    // {
    //     return User::count();
    // }
    // public function getTotalSalesProperty()
    // {
    //     return User::sum('sales');
    // }
    // public function getTotalRevenueProperty()
    // {
    //     return User::sum('revenue');
    // }
    // public function getTotalProductsProperty()
    // {
    //     return User::sum('products');
    // }
    // public function getTotalOrdersProperty()
    // {
    //     return User::sum('orders');
    // }
    // public function getTotalCustomersProperty()
    // {
    //     return User::sum('customers');
    // }
    // public function getTotalSuppliersProperty()
    // {
    //     return User::sum('suppliers');
    // }
}; ?>

<div class="flex flex-col gap-6">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <x-stat icon="users" title="Total users" number="10,500" tooltip="User decrease" decrease="5%" primary />
        <x-stat icon="shopping-cart" title="Total sales" number="12,345" tooltip="Sales increase" increase="10%" amber/>
        <x-stat icon="currency-dollar" title="Total revenue" number="$123,456" tooltip="Revenue increase" increase="15%" cyan/>
        <x-stat icon="archive-box-arrow-down" title="Total products" number="890" tooltip="Products increase" increase="8%" indigo/>
    </div>
    <x-card>
        <x-card-header title="Recent Orders"/>
        <x-card-content>
            <x-table :paginate="$users" :per-page="[5, 10, 20, 50]" searchable>
                <x-slot:heading>
                    <x-th label="#" />
                    <x-th label="Name" sortable :$sortCol :$sortAsc />
                    <x-th label="Email" sortable :$sortCol :$sortAsc />
                    <x-th label="Active" />
                    <x-th label="Admin" />
                    <x-th label="Actions" />
                </x-slot:heading>
            
                @forelse ($users as $index => $user)
                    <x-tr>
                        <x-td :label="$users->firstItem() + $index" />
                        <x-td class="flex items-center gap-2" >
                            <x-avatar alt="Avatar" sm rounded-full/>
                            <span>
                                {{ $user->name }}
                            </span>
                        </x-td>
                        <x-td :label="$user->email" />
                        <x-td>
                            @if ($user->is_active)
                                <x-icon name="check-circle" class="text-green-500 size-6" />
                            @else
                                <x-icon name="x-circle" class="text-amber-500 size-6" />
                            @endif
                        </x-td>
                        <x-td>
                            @if ($user->is_admin)
                            <x-badge label="Admin" green class="select-none" />
                            @else
                            <x-badge label="User" amber class="select-none" />
                            @endif
                        </x-td>
                        <x-td class="space-x-2">
                            <x-dropdown>
                                <x-slot:trigger>
                                    <x-button icon="ellipsis-vertical" flat gray sm />
                                </x-slot:trigger>
            
                                <x-dropdown-item label="View" icon="eye" />
                                <x-dropdown-item label="Edit" icon="pencil-square" />
                                <x-dropdown-item label="Delete" icon="trash" />
                            </x-dropdown>
                        </x-td>
                    </x-tr>
                @empty
                    <x-not-found />
                @endforelse
                
                <x-slot:filters>
                    <x-dropdown title="Filters" persistent w-56>
                        <x-slot:trigger>
                            <x-button icon="funnel" flat gray sm />
                        </x-slot:trigger>
            
                        <div class="p-4 space-y-3">
                            <x-toggle wire:model.live="is_active" label="Active" />
                            <x-toggle wire:model.live="is_admin" label="Admin" />
                            <x-toggle wire:model.live="email_verified_at" label="Email verified" />
                        </div>
                    </x-dropdown>
                </x-slot:filters>
            </x-table>
        </x-card-content>
        <x-card-footer>
            <x-button label="View All Orders" link />
        </x-card-footer>
    </x-card>
</div>
