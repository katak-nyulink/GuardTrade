<?php

use App\Models\User;
use App\Models\Menu;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Customer;
use Carbon\Carbon;
use Livewire\Volt\Component;
use Illuminate\View\View;
use Livewire\Attributes\{Layout, Title};
use Developermithu\Tallcraftui\Traits\WithTcTable;

new #[Title('Dashboard')] class
    // #[Layout('components.layouts.app')]
    extends Component {
    use WithTcTable;
 
    public $salesToday;
    public $salesYesterday;
    public $salesGrowth;
    public $purchasesToday; 
    public $purchasesYesterday;
    public $purchasesGrowth;
    public $totalProducts;
    public $productsLastWeek;
    public $productsGrowth;
    public $totalCustomers;
    public $customersLastWeek; 
    public $customersGrowth;
    public $totalRevenue;
    public $revenueLastMonth;
    public $revenueGrowth;
    public $lowStockProducts;

    // public $listeners = ['refresh' => '$refresh'];
    public bool $is_active = false;
    public bool $is_admin = false;
    public bool $email_verified_at = false;

    public array $columns = [
        'name' => ['label' => 'Name', 'sortable' => true],
        // 'email' => ['label' => 'Email', 'sortable' => true],
        // 'is_active' => ['label' => 'Active', 'sortable' => false],
        'role' => ['label' => 'Role', 'sortable' => false],
        'created_at' => ['label' => 'Joined At', 'sortable' => true],
        // 'actions' => ['label' => 'Actions', 'sortable' => false],
    ];

    // public $menus;

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
                ->simplePaginate(5),
        ];
    }

    public function mount()
    {
        // app('menu')->getMenuStructure();
        // app('menu')->clearCache();
        // dd(app('menu')->getMenuStructure()->toArray());
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $lastWeek = Carbon::today()->subWeek();
        $lastMonth = Carbon::today()->subMonth();

        // Sales calculations
        $salesData = [
            'today' => Sale::whereDate('date', $today)->sum('total_amount'),
            'yesterday' => Sale::whereDate('date', $yesterday)->sum('total_amount'),
            'monthly' => Sale::whereMonth('date', $today->month)->sum('total_amount'),
            'lastMonth' => Sale::whereMonth('date', $lastMonth->month)->sum('total_amount')
        ];

        $this->salesToday = Number::currency($salesData['today']);
        $this->salesYesterday = $salesData['yesterday'];
        $this->salesGrowth = $this->calculateGrowth($salesData['today'], $salesData['yesterday']);

        // Customers calculations 
        $customersData = Customer::selectRaw('
            COUNT(*) as total,
            COUNT(CASE WHEN created_at < ? THEN 1 END) as last_week
        ', [$lastWeek])->first();

        $this->totalCustomers = $customersData->total;
        $this->customersLastWeek = $customersData->last_week;
        $this->customersGrowth = $this->calculateGrowth($this->totalCustomers, $this->customersLastWeek);

        // Products calculations
        $productsData = Product::selectRaw('
            COUNT(*) as total,
            COUNT(CASE WHEN created_at < ? THEN 1 END) as last_week
        ', [$lastWeek])->first();

        $this->totalProducts = $productsData->total;
        $this->productsLastWeek = $productsData->last_week;
        $this->productsGrowth = $this->calculateGrowth($this->totalProducts, $this->productsLastWeek);

        // Revenue calculations
        $this->totalRevenue = Number::currency($salesData['monthly']);
        $this->revenueLastMonth = $salesData['lastMonth'];
        $this->revenueGrowth = $this->calculateGrowth($salesData['monthly'], $salesData['lastMonth']);

        $this->purchasesToday = Number::currency(Purchase::whereDate('date', $today)->sum('total_amount'));
        $this->lowStockProducts = 0; // Placeholder
    }

    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) return 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }
    // public function mount()
    // {
        // $this->breadcrumbs()
        // ->add('dashboard', route('dashboard'))
        // ->add($product->category->name, route('products.category', $product->category))
        // ->add('Singmisi');
    //     foreach ($this->columns as $key => $column) {
    //         $this->columns[$key]['label'] = __($column['label']);
    //     }
    //     // $this->columns['name']['label'] = __('Name'); // Set translation at runtime
    //     // $this->menus= Menu::with('children')->whereNull('parent_id')->latest()->get();
    // }
}; ?>

<div class="flex flex-col gap-6">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <x-stat 
            icon="users" 
            title="Total Customer" 
            :number="$totalCustomers" 
            :tooltip="$customersGrowth >= 0 ? 'Customer increase' : 'Customer decrease'"
            :increase="$customersGrowth > 0 ?? Number::percentage($customersGrowth )"
            :decrease="$customersGrowth < 0 ?? Number::percentage(abs($customersGrowth) )"
            class="shadow-lg" 
            rounded-lg 
        />
        <x-stat 
            icon="shopping-cart" 
            title="Total sales" 
            :number="$salesToday" 
            :tooltip="$salesGrowth >= 0 ? 'Sales increase' : 'Sales decrease'"
            :increase="$salesGrowth > 0 ?? Number::percentage($salesGrowth )"
            :decrease="$salesGrowth < 0 ?? Number::percentage(abs($salesGrowth))"
            amber
            rounded-lg 
            class="shadow-lg" 
        />
        <x-stat 
            icon="currency-dollar" 
            title="Total revenue" 
            :number="$totalRevenue" 
            :tooltip="$revenueGrowth >= 0 ? 'Revenue increase' : 'Revenue decrease'"
            :increase="$revenueGrowth > 0 ?? Number::percentage($revenueGrowth)"
            :decrease="$revenueGrowth < 0 ?? Number::percentage(abs($revenueGrowth))"
            cyan 
            rounded-lg 
            class="shadow-lg" 
        />
        <x-stat 
            icon="archive-box-arrow-down" 
            title="Total products" 
            :number="$totalProducts"
            :tooltip="$productsGrowth >= 0 ? ($productsGrowth > 0 ? 'Products increase' : 'above average'):'Products decrease'" 
            :increase="$productsGrowth > 0 ?? Number::percentage($productsGrowth)"
            :decrease="$productsGrowth < 0 ?? Number::percentage(abs($productsGrowth))"
            indigo 
            rounded-lg 
            class="shadow-lg" 
        />
    </div>
        {{-- <x-separator/> --}}
        <x-table searchable class="bg-white dark:bg-slate-800 overflow-hidden">
            <x-slot:heading>
                {{-- <x-th label="#" /> --}}
                @foreach ($this->columns as $key => $column)
                    <x-th :sortable="$column['sortable']" :$sortCol :$sortAsc>
                        <span>{{ __('crud.users.inputs.' . $key . '.label') }}</span>
                    </x-th>
                @endforeach
                {{-- <x-th label="Name" sortable :$sortCol :$sortAsc />
            <x-th label="Email" sortable :$sortCol :$sortAsc />
            <x-th label="Active" />
            <x-th label="Admin" /> --}}
                {{-- <x-th label="Actions" /> --}}
            </x-slot:heading>
            {{-- @dd($users) --}}

            @forelse ($users as $index => $user)
                <x-tr>
                    {{-- <x-td :label="$users->firstItem() + $index" /> --}}
                    <x-td>
                        <div class="flex items-center gap-4 *:first:inline-flex">
                            <x-avatar alt="Avatar" sm rounded-full />
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                <div class="mt-1">{{ $user->email }}</div>
                            </div>
                        </div>
                    </x-td>
                    {{-- <x-td :label="$user->email" /> --}}
                    {{-- <x-td> --}}
                        {{-- <x-badge label="{{ $user->is_active->label() }}" {{ $user->is_active->color() }}
                            class="select-none" /> --}}
                        {{-- <x-dynamic-component component="badge" label="{{ $user->is_active->label() }}"
                            {{ $user->is_active->color() }} /> --}}
                        {{-- @if ($user->is_active)
                            <x-icon name="check-circle" class="text-green-500 size-6" />
                        @else
                            <x-icon name="x-circle" class="text-amber-500 size-6" />
                        @endif --}}
                    {{-- </x-td> --}}
                    <x-td>
                        @if ($user->is_admin === App\Enums\YesNo::YES)
                            <x-badge label="Admin" green class="select-none" />
                        @else
                            <x-badge label="User" amber class="select-none" />
                        @endif
                    </x-td>
                    <x-td :label="$user->created_at->diffForHumans()" />
                    {{-- <x-td>
                        <x-dropdown w-32>
                            <x-slot:trigger>
                                <x-button icon="ellipsis-vertical" flat gray xs class="px-1.5" />
                            </x-slot:trigger>

                            <x-dropdown-item label="View" icon="eye" />
                            <x-dropdown-item label="Edit" icon="pencil-square" />
                            <x-dropdown-item label="Delete" icon="trash" />
                        </x-dropdown>
                    </x-td> --}}
                </x-tr>
            @empty
                <x-not-found />
            @endforelse

            <x-slot:bulkActions>
                <h2 class="text-lg font-medium text-gray-800 sm:text-xl dark:text-gray-200">{{ __('crud.users.description') }}</h2>
            </x-slot:bulkActions>

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


    {{-- <x-tree-table 
    :items="$this->menus" 
    :headers="['title',
        'route',
        'icon',
        'parent_id',
        'permission_name',
        'order',
        'group',
        'type',]"
/> --}}

    {{--     
<app-custom-tree class="overflow-x-auto">
    <mat-tree  role="tree" class="">
        <mat-nested-tree-node x-data="{showTree:false}"
            class="mat-nested-tree-node w-full mb-4 min-h-12 break-normal py-1.5 dark:bg-slate-800 shadow-lg" role="treeitem"
            aria-level="1" :aria-expanded="showTree">
            <div  class="wrap-node grid grid-cols-[47px_auto]">
                <div class="py-1.5">
                    <x-button x-on:click="showTree = !showTree" icon="chevron-right" flat circle sm  class="my-auto mx-auto text-slate-700 dark:text-slate-400" ::class="{'rotate-90':showTree}"/>
                </div>
                <div  style="width: 100%;">
                    <div  class="mat-tree-node grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start">
                        <div  class="account-name ml-2 leading-[1.6]">
                            <div >Aktiva</div>
                            <div ><b >1000</b></div>
                        </div>
                        <div >Rp 24,567,000.00</div>
                        <div  class="action-coa not-editable-coa">
                            <x-button icon="pencil" flat sm label="Ubah"/>
                        </div>
                        <div  class="coa-switch-column">
                            <x-toggle checked sm />
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="showTree" x-collapse.duration.300ms role="group" class="pl-8">
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Kas</div>
                                    <div ><b >1101</b></div>
                                </div>
                                <div >Rp 215,000.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Bank</div>
                                    <div ><b >1201</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Bank Central Asia #6110341863</div>
                                    <div ><b >1202</b></div>
                                </div>
                                <div >Rp 6,200,000.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Paypal #paypal.me</div>
                                    <div ><b >1203</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >BNI-CC #1234</div>
                                    <div ><b >1204</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Rekening Bersama Digital Payment Paper.id</div>
                                    <div ><b >1251</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Piutang Usaha</div>
                                    <div ><b >1301</b></div>
                                </div>
                                <div >Rp 5,252,000.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Persediaan</div>
                                    <div ><b >1501</b></div>
                                </div>
                                <div >Rp 12,900,000.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Persediaan dalam Perjalanan</div>
                                    <div ><b >1502</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Persediaan Konsinyasi</div>
                                    <div ><b >1503</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >PPN Masukan</div>
                                    <div ><b >1701</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    aria-expanded="true">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >PPH Pasal 23 Dibayar Dimuka</div>
                                    <div ><b >1702</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Bangunan</div>
                                    <div ><b >1801</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Peralatan</div>
                                    <div ><b >1802</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Kendaraan</div>
                                    <div ><b >1803</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Akumulasi Penyusutan Bangunan</div>
                                    <div ><b >1851</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Akumulasi Penyusutan Peralatan</div>
                                    <div ><b >1852</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Akumulasi Penyusutan Kendaraan</div>
                                    <div ><b >1853</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <!---->
            </div>
        </mat-nested-tree-node>
        <mat-nested-tree-node x-data="{showTree:false}" 
            class="mat-nested-tree-node w-full mb-4 min-h-12 break-normal py-1.5 dark:bg-slate-800 shadow-lg" role="treeitem"
            aria-level="1" :aria-expanded="showTree">
            <div  class="wrap-node grid grid-cols-[47px_auto]">
                <div class="py-1.5">
                    <x-button x-on:click="showTree = !showTree" icon="chevron-right" flat circle sm  class="my-aut mx-autoo" ::class="{'rotate-90':showTree}"/>
                </div>
                <div  style="width: 100%;">
                    <div  class="mat-tree-node accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                        <div  class="account-name ml-2 leading-[1.6]">
                            <div >Kewajiban</div>
                            <div ><b >2000</b></div>
                        </div>
                        <div >Rp 0.00</div>
                        <div  class="action-coa not-editable-coa">
                            <x-button icon="pencil" flat sm label="Ubah"/>
                        </div>
                        <div  class="coa-switch-column">
                            <x-toggle checked sm />
                        </div>
                    </div>
                </div>
            </div>
            <div  x-show="showTree" x-collapse.duration.300ms role="group" class="pl-8">
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Hutang Usaha</div>
                                    <div ><b >2101</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Pendapatan Diterima Di Muka</div>
                                    <div ><b >2201</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Penjualan dimuka in transit</div>
                                    <div ><b >2203</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Hutang PPH Pasal 21</div>
                                    <div ><b >2301</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Hutang PPH Pasal 23</div>
                                    <div ><b >2302</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Hutang PPH Pasal 4(2)</div>
                                    <div ><b >2303</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Hutang PPH Pasal 25</div>
                                    <div ><b >2304</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >PPN Keluaran</div>
                                    <div ><b >2305</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Hutang PPN</div>
                                    <div ><b >2306</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Hutang Bank</div>
                                    <div ><b >2401</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <!---->
            </div>
        </mat-nested-tree-node>
        <mat-nested-tree-node x-data="{showTree:false}" 
            class="mat-nested-tree-node w-full mb-4 min-h-12 break-normal py-1.5 dark:bg-slate-800 shadow-lg" role="treeitem"
            aria-level="1" :aria-expanded="showTree">
            <div  class="wrap-node grid grid-cols-[47px_auto]">
                <div class="py-1.5">
                    <x-button x-on:click="showTree = !showTree" icon="chevron-right" flat circle sm  class="my-aut mx-autoo" ::class="{'rotate-90':showTree}"/>
                </div>
                <div  style="width: 100%;">
                    <div  class="mat-tree-node accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                        <div  class="account-name ml-2 leading-[1.6]">
                            <div >Modal</div>
                            <div ><b >3000</b></div>
                        </div>
                        <div >Rp 12,900,000.00</div>
                        <div  class="action-coa not-editable-coa">
                            <x-button icon="pencil" flat sm label="Ubah"/>
                        </div>
                        <div  class="coa-switch-column">
                            <x-toggle checked sm />
                        </div>
                    </div>
                </div>
            </div>
            <div  x-show="showTree" x-collapse.duration.300ms role="group" class="pl-8">
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Modal Disetor</div>
                                    <div ><b >3101</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Laba Ditahan</div>
                                    <div ><b >3201</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Laba Tahun Berjalan</div>
                                    <div ><b >3202</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Dividen</div>
                                    <div ><b >3301</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Saldo Ekuitas Awal</div>
                                    <div ><b >3991</b></div>
                                </div>
                                <div >Rp 12,900,000.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <!---->
            </div>
        </mat-nested-tree-node>
        <mat-nested-tree-node x-data="{showTree:false}" 
            class="mat-nested-tree-node w-full mb-4 min-h-12 break-normal py-1.5 dark:bg-slate-800 shadow-lg" role="treeitem"
            aria-level="1" :aria-expanded="showTree">
            <div  class="wrap-node grid grid-cols-[47px_auto]">
                <div class="py-1.5">
                    <x-button x-on:click="showTree = !showTree" icon="chevron-right" flat circle sm  class="my-aut mx-autoo" ::class="{'rotate-90':showTree}"/>
                </div>
                <div  style="width: 100%;">
                    <div  class="mat-tree-node accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                        <div  class="account-name ml-2 leading-[1.6]">
                            <div >Pendapatan</div>
                            <div ><b >4000</b></div>
                        </div>
                        <div >Rp 12,967,000.00</div>
                        <div  class="action-coa not-editable-coa">
                            <x-button icon="pencil" flat sm label="Ubah"/>
                        </div>
                        <div  class="coa-switch-column">
                            <x-toggle checked sm />
                        </div>
                    </div>
                </div>
            </div>
            <div  x-show="showTree" x-collapse.duration.300ms role="group" class="pl-8">
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Penjualan Umum</div>
                                    <div ><b >4101</b></div>
                                </div>
                                <div >Rp 12,967,000.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Pendapatan Jasa</div>
                                    <div ><b >4102</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Penjualan Produk</div>
                                    <div ><b >4103</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Pendapatan Pengiriman</div>
                                    <div ><b >4110</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Diskon Penjualan</div>
                                    <div ><b >4201</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Retur Penjualan</div>
                                    <div ><b >4301</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <!---->
            </div>
        </mat-nested-tree-node>
        <mat-nested-tree-node x-data="{showTree:false}" 
            class="mat-nested-tree-node w-full mb-4 min-h-12 break-normal py-1.5 dark:bg-slate-800 shadow-lg" role="treeitem"
            aria-level="1" :aria-expanded="showTree">
            <div  class="wrap-node grid grid-cols-[47px_auto]">
                <div class="py-1.5">
                    <x-button x-on:click="showTree = !showTree" icon="chevron-right" flat circle sm  class="my-aut mx-autoo" ::class="{'rotate-90':showTree}"/>
                </div>
                <div  style="width: 100%;">
                    <div  class="mat-tree-node accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                        <div  class="account-name ml-2 leading-[1.6]">
                            <div >Harga Pokok Penjualan</div>
                            <div ><b >5000</b></div>
                        </div>
                        <div >Rp 0.00</div>
                        <div  class="action-coa not-editable-coa">
                            <x-button icon="pencil" flat sm label="Ubah"/>
                        </div>
                        <div  class="coa-switch-column">
                            <x-toggle checked sm />
                        </div>
                    </div>
                </div>
            </div>
            <div  x-show="showTree" x-collapse.duration.300ms role="group" class="pl-8">
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Harga Pokok Penjualan</div>
                                    <div ><b >5101</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Pengiriman</div>
                                    <div ><b >5102</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Pembelian</div>
                                    <div ><b >5103</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Diskon Pembelian</div>
                                    <div ><b >5201</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Retur Pembelian</div>
                                    <div ><b >5301</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <!---->
            </div>
        </mat-nested-tree-node>
        <mat-nested-tree-node x-data="{showTree:false}" 
            class="mat-nested-tree-node w-full mb-4 min-h-12 break-normal py-1.5 dark:bg-slate-800 shadow-lg" role="treeitem"
            aria-level="1" :aria-expanded="showTree">
            <div  class="wrap-node grid grid-cols-[47px_auto]">
                <div class="py-1.5">
                    <x-button x-on:click="showTree = !showTree" icon="chevron-right" flat circle sm  class="my-aut mx-autoo" ::class="{'rotate-90':showTree}"/>
                </div>
                <div  style="width: 100%;">
                    <div  class="mat-tree-node accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                        <div  class="account-name ml-2 leading-[1.6]">
                            <div >Beban</div>
                            <div ><b >6000</b></div>
                        </div>
                        <div >Rp 700,000.00</div>
                        <div  class="action-coa not-editable-coa">
                            <x-button icon="pencil" flat sm label="Ubah"/>
                        </div>
                        <div  class="coa-switch-column">
                            <x-toggle checked sm />
                        </div>
                    </div>
                </div>
            </div>
            <div  x-show="showTree" x-collapse.duration.300ms role="group" class="pl-8">
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Gaji Operasional</div>
                                    <div ><b >6101</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Gaji Administrasi</div>
                                    <div ><b >6102</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Biaya Pencairan Digital Payment</div>
                                    <div ><b >6121</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Biaya Pembayaran Keluar</div>
                                    <div ><b >6199</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Listrik dan Air</div>
                                    <div ><b >6201</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Kendaraan dan Transportasi</div>
                                    <div ><b >6202</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Komunikasi</div>
                                    <div ><b >6203</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Perlengkapan Kantor</div>
                                    <div ><b >6204</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Komisi Penjualan</div>
                                    <div ><b >6301</b></div>
                                </div>
                                <div >Rp 700,000.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Entertainment</div>
                                    <div ><b >6302</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Iklan dan Promosi</div>
                                    <div ><b >6303</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Perbaikan dan Pemeliharaan</div>
                                    <div ><b >6401</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Sewa</div>
                                    <div ><b >6501</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Asuransi</div>
                                    <div ><b >6502</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Penyesuaian Persediaan</div>
                                    <div ><b >6601</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Cacat Produksi</div>
                                    <div ><b >6602</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Perijinan dan Lisensi</div>
                                    <div ><b >6701</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Penyusutan Bangunan</div>
                                    <div ><b >6801</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Penyusutan Peralatan</div>
                                    <div ><b >6802</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Penyusutan Kendaraan</div>
                                    <div ><b >6803</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Piutang Tak Tertagih</div>
                                    <div ><b >6901</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <!---->
            </div>
        </mat-nested-tree-node>
        <mat-nested-tree-node x-data="{showTree:false}" 
            class="mat-nested-tree-node w-full mb-4 min-h-12 break-normal py-1.5 dark:bg-slate-800 shadow-lg" role="treeitem"
            aria-level="1" :aria-expanded="showTree">
            <div  class="wrap-node grid grid-cols-[47px_auto]">
                <div class="py-1.5">
                    <x-button x-on:click="showTree = !showTree" icon="chevron-right" flat circle sm  class="my-aut mx-autoo" ::class="{'rotate-90':showTree}"/>
                </div>
                <div  style="width: 100%;">
                    <div  class="mat-tree-node accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                        <div  class="account-name ml-2 leading-[1.6]">
                            <div >Pendapatan Lain-lain</div>
                            <div ><b >7001</b></div>
                        </div>
                        <div >Rp 0.00</div>
                        <div  class="action-coa">
                            <x-button icon="pencil" flat sm label="Ubah"/>
                        </div>
                        <div  class="coa-switch-column">
                            <x-toggle checked sm />
                        </div>
                    </div>
                </div>
            </div>
            <div  x-show="showTree" x-collapse.duration.300ms role="group" class="pl-8">
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Pendapatan Bunga</div>
                                    <div ><b >7101</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Keuntungan dari Selisih Kurs</div>
                                    <div ><b >7120</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Keuntungan Dari Penjualan Aktiva Tetap</div>
                                    <div ><b >7201</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <!---->
            </div>
        </mat-nested-tree-node>
        <mat-nested-tree-node x-data="{showTree:false}" 
            class="mat-nested-tree-node w-full mb-4 min-h-12 break-normal py-1.5 dark:bg-slate-800 shadow-lg" role="treeitem"
            aria-level="1" :aria-expanded="showTree">
            <div  class="wrap-node grid grid-cols-[47px_auto]">
                <div class="py-1.5">
                    <x-button x-on:click="showTree = !showTree" icon="chevron-right" flat circle sm  class="my-aut mx-autoo" ::class="{'rotate-90':showTree}"/>
                </div>
                <div  style="width: 100%;">
                    <div  class="mat-tree-node accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                        <div  class="account-name ml-2 leading-[1.6]">
                            <div >Beban Lain-lain</div>
                            <div ><b >8001</b></div>
                        </div>
                        <div >Rp 600,000.00</div>
                        <div  class="action-coa">
                            <x-button icon="pencil" flat sm label="Ubah"/>
                        </div>
                        <div  class="coa-switch-column">
                            <x-toggle checked sm />
                        </div>
                    </div>
                </div>
            </div>
            <div  x-show="showTree" x-collapse.duration.300ms role="group" class="pl-8">
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Bunga</div>
                                    <div ><b >8101</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Beban Administrasi Bank</div>
                                    <div ><b >8102</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Kerugian Selisih Kurs</div>
                                    <div ><b >8120</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <mat-tree-node  
                    class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="2"
                    :aria-expanded="showTree">
                    <div  class="wrap-node grid grid-cols-[47px_auto]">
                        <div ></div>
                        <div  style="width: 100%;">
                            <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                                <div  class="account-name ml-2 leading-[1.6]">
                                    <div >Kerugian Dari Penjualan Aktiva Tetap</div>
                                    <div ><b >8201</b></div>
                                </div>
                                <div >Rp 0.00</div>
                                <div  class="action-coa">
                                    <x-button icon="pencil" label="Ubah" flat sm/>
                                </div>
                                <div  class="coa-switch-column">
                                    <x-toggle checked sm />
                                </div>
                            </div>
                        </div>
                    </div>
                </mat-tree-node>
                <!---->
            </div>
        </mat-nested-tree-node>
        <mat-tree-node  
            class="mat-tree-node cdk-tree-node ng-star-inserted" role="treeitem" aria-level="1" aria-expanded="false">
            <div  class="wrap-node grid grid-cols-[47px_auto]">
                <div ></div>
                <div  style="width: 100%;">
                    <div  class="accounting-div grid grid-cols-[auto_420px_100px_100px] mb-4 text-[#133f5d] bg-white shadow-[0_4px_8px_#c2dbed] dark:bg-slate-800 dark:shadow-slate-950 h-15 items-center justify-items-start ">
                        <div  class="account-name ml-2 leading-[1.6]">
                            <div >Beban Pajak Penghasilan</div>
                            <div ><b >9000</b></div>
                        </div>
                        <div >Rp 0.00</div>
                        <div  class="action-coa">
                            <x-button icon="pencil" flat sm label="Ubah"/>
                        </div>
                        <div  class="coa-switch-column">
                            <x-toggle checked sm />
                        </div>
                    </div>
                </div>
            </div>
        </mat-tree-node>
        <!---->
    </mat-tree>
</app-custom-tree> --}}

</div>
