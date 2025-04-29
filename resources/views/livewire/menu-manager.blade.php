<x-card class="max-w-md">
    <x-card-header title="Menu Manager" subtitle="Create an account and manage your profile." />

    <x-card-content>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Menu Items</h2>
        <x-button wire:click="addMenu" label="Add Menu" primary/>
    </div>
    <form wire:submit.prevent="save" class="space-y-2 mb-4">
        <x-input wire:model.defer="title" placeholder="Title" />
        <x-input wire:model.defer="route" placeholder="Route (optional)" />
        <x-input wire:model.defer="icon" placeholder="Icon (e.g., home, settings)" />
        <x-select wire:model.defer="type">
            <option value="link">Link</option>
            <option value="dropdown">Dropdown</option>
        </x-select>
        <x-input wire:model.defer="group" placeholder="Group (optional)" />
        <x-input wire:model.defer="permission_name" placeholder="Permission name" />
        <x-select wire:model.defer="parent_id">
            <option value="">No Parent</option>
            @foreach ($menus as $topMenu)
                <option value="{{ $topMenu->id }}">{{ $topMenu->title }}</option>
            @endforeach
        </x-select>
        <x-button type="submit" label="Save"/>
        @if ($menu_id)
            <x-button wire:click="resetInput" label="Cancel" red/>
        @endif
    </form>

    <div id="menu-nestable" class="dd">
        {{-- {!! buildMenuNestable($menus) !!} --}}
        {{-- <x-build-menu-nestable :menus="$menus" /> --}}
        <ol class="dd-list">
            @foreach ($menus as $menu)
                <x-build-menu-nestable :menu="$menu" />
            @endforeach
        </ol>
    </div>
    </x-card-content>
    <x-card-footer>
        <x-button wire:click="addMenu" label="Add Menu" primary/>
        <x-button wire:click="deleteSelected" label="Delete Selected" red/>
        <x-button wire:click="saveOrder" label="Save Order" primary/>
    </x-card-footer>
</x-card>

<div class="overflow-x-auto">
    {{-- @dd($menus2->items()) --}}
    <x-table :paginate="$menus2" :per-page="[5, 10, 20, 50]" searchable>
        <x-slot:heading>
            <x-th label="#" />
            @foreach ($this->columns as $column)
                <x-th :label="$column['label']" :sortable="$column['sortable']" :$sortCol :$sortAsc />
            @endforeach
            {{-- <x-th label="Name" sortable :$sortCol :$sortAsc />
            <x-th label="Email" sortable :$sortCol :$sortAsc />
            <x-th label="Active" />
            <x-th label="Admin" /> --}}
            {{-- <x-th label="Actions" /> --}}
        </x-slot:heading>

        @forelse ($menus2 as $index => $menu)
            <x-tr>
                <x-td>
                    <x-checkbox wire:model="selected" :value="$menu->id" />
                </x-td>
                @foreach ($this->columns as $key => $column)
                    @if ($key === 'action')
                        <x-td>
                            <div class="flex space-x-2">
                                <x-button wire:click="edit({{ $menu->id }})" icon="pencil" xs />
                                <x-button wire:click="delete({{ $menu->id }})" icon="trash" xs red />
                            </div>
                        </x-td>
                    @else
                        <x-td :label="$this->formatColumnValue($key, $menu)" />
                    @endif
                @endforeach
            </x-tr>
        @empty
            <x-not-found />
        @endforelse
    </x-table>
</div>
@push('other-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nestable2@1.6.0/jquery.nestable.min.css">
    <style>
        .dd-handle {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            cursor: move;
            background: #f3f4f6;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            margin-bottom: 5px;
        }
    </style>
    
@endpush

@push('other-scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nestable2@1.6.0/jquery.nestable.min.js"></script>

    <script>
        document.addEventListener('livewire:load', () => {
            // const initNestable = () => {
            //     $('#menu-nestable').nestable({ maxDepth: 3 }).on('change', function () {
            //         let list = $(this).nestable('serialize');
            //         Livewire.emit('reorder', list);
            //     });
            // };

            function initNestable() {
                const nestable = $('#menu-nestable');
                if (!nestable.hasClass('ready')) {
                    nestable.nestable({ maxDepth: 3 }).on('change', function () {
                        const data = $(this).nestable('serialize');
                        Livewire.emit('reorder', data);
                    });
                    nestable.addClass('ready');
                }
            }

            initNestable();

            Livewire.on('refreshNestable', () => {
                $('#menu-nestable').nestable('destroy');
                $('#menu-nestable').removeClass('ready');
                initNestable();
            });
        });
    </script>
@endpush