<div class="overflow-x-auto">
<div x-data="{ 
    openItems: {},
    toggleItem(id) {
        if (!this.openItems[id]) {
            this.openItems[id] = true;
        } else {
            // Close the item and all its children
            Object.keys(this.openItems).forEach(key => {
                if (document.querySelector(`[data-parent='${id}']`) && document.querySelector(`[data-parent='${id}']`).contains(document.getElementById(key))) {
                    this.openItems[key] = false;
                }
            });
            this.openItems[id] = false;
        }
    }
}">
{{-- {{ __('footer')['privacy_policy'] }} --}}

    <table class="table">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                @foreach($columns as $key => $column)
                    <th style="--col-width: {{ $column['width'] }};" class="px-4 py-3 text-left text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[var(--col-width)]">
                        {{ __($column['label']) }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="text-sm bg-white dark:bg-gray-900 divide-y">
            @if($menus->isEmpty())
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                        {{ __('No menus found.') }}
                    </td>
                </tr>
            @endif

            @foreach($menus as $menu)
                <tr id="{{ $menu->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-800"
                    @foreach ($columns as $key => $column )
                        @if ($key === 'title')
                            <td style="--col-width: {{ $column['width'] }};" class="px-4 py-3 whitespace-nowrap w-[var(--col-width)]">
                                <div class="flex items-center gap-2">
                                        <button class="p-1 transition duration-200 cursor-pointer text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 {{ $menu->children->count() > 0 ? 'visible' : 'invisible size-5.5' }}" 
                                            @if($menu->children->count() > 0)
                                                @click="toggleItem({{ $menu->id }})"
                                            @endif
                                            >
                                            @if($menu->children->count() > 0)
                                                <x-heroicon-s-chevron-right class="size-3.5 transition-transform duration-200" ::class="{ 'rotate-90': openItems[{{ $menu->id }}] }"/>
                                            @endif
                                        </button>
                                    <span class="dark:text-gray-200">{{ $menu->title }}</span>
                                </div>
                            </td>
                        @elseif ($key === 'action')
                            <td style="--col-width: {{ $column['width'] }};" class="px-4 py-3 w-[var(--col-width)]">
                                <button class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-2" wire:click="edit({{ $menu->id }})">
                                    {{ __('Edit') }}
                                </button>
                                <button class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" wire:click="delete({{ $menu->id }})">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        @else
                            <td style="--col-width: {{ $column['width'] }};" class="px-4 py-3 text-left text-sm w-[var(--col-width)]">
                                @if ($key === 'permission_name')
                                    @php
                                        $permissions = explode(' ',$menu->permission_name);
                                    @endphp
                                    <ul class="flex flex-wrap items-center gap-1.5 *:data-permission:inline-flex *:data-permission:items-center *:data-permission:text-xs *:data-permission:font-medium *:data-permission:px-2 *:data-permission:py-0.5 *:data-permission:rounded-full *:data-permission:bg-sky-100 *:data-permission:text-sky-800 dark:*:data-permission:bg-sky-900 dark:*:data-permission:text-sky-300">
                                        @if($permissions === [''])
                                            <li class="text-amber-400">{{ __('No permission assigned') }}</li>
                                        @else
                                            @foreach($permissions as $permission)
                                                <li data-permission>
                                                    {{ $permission }}
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                @else
                                    {{ $menu->$key }}
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>

                @foreach($menu->children as $child)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800"
                        x-show="openItems[{{ $menu->id }}]"
                        id="{{ $child->id }}"
                        data-parent="{{ $menu->id }}"
                        x-cloak
                        :aria-expanded="openItems[{{ $menu->id }}]"
                        x-collapse.duration.300ms
                        @foreach ($columns as $key => $column )
                            @if ($key === 'title')
                                <td style="--col-width: {{ $column['width'] }};" class="px-4 py-3 whitespace-nowrap w-[var(--col-width)]">
                                    <div class="flex items-center gap-2">
                                            <button class="ml-4 p-1 transition duration-200 cursor-pointer text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 {{ $child->children->count() > 0 ? 'visible' : 'invisible size-5.5' }}" 
                                                @if($child->children->count() > 0)
                                                    @click="toggleItem({{ $child->id }})"
                                                @endif
                                                >
                                                @if($child->children->count() > 0)
                                                    <x-heroicon-s-chevron-right class="size-3.5 transition-transform duration-200" ::class="{ 'rotate-90': openItems[{{ $child->id }}] }"/>
                                                @endif
                                            </button>
                                        <span class="dark:text-gray-200">{{ $child->title }}</span>
                                    </div>
                                </td>
                            @elseif ($key === 'action')
                                <td style="--col-width: {{ $column['width'] }};" class="px-4 py-3 w-[var(--col-width)]">
                                    <button class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-2" wire:click="edit({{ $child->id }})">
                                        {{ __('Edit') }}
                                    </button>
                                    <button class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" wire:click="delete({{ $child->id }})">
                                        {{ __('Delete') }}
                                    </button>
                                </td>
                            @else
                                <td style="--col-width: {{ $column['width'] }};" class="px-4 py-3 text-left text-sm w-[var(--col-width)]">
                                    @if ($key === 'permission_name')
                                    @php
                                        $permissions = explode(' ',$child->permission_name);
                                    @endphp
                                    <ul class="flex flex-wrap items-center gap-1.5 *:data-permission:inline-flex *:data-permission:items-center *:data-permission:text-xs *:data-permission:font-medium *:data-permission:px-2 *:data-permission:py-0.5 *:data-permission:rounded-full *:data-permission:bg-sky-100 *:data-permission:text-sky-800 dark:*:data-permission:bg-sky-900 dark:*:data-permission:text-sky-300">
                                        @if($permissions === [''])
                                            <li class="text-amber-400">{{ __('No permission assigned') }}</li>
                                        @else
                                            @foreach($permissions as $permission)
                                                <li data-permission>
                                                    {{ $permission }}
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                @else
                                    {{ $child->$key }}
                                @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    @foreach($child->children as $subChild)
                        {{-- Sub Child --}} 
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800"
                            x-show="openItems[{{ $menu->id }}] && openItems[{{ $child->id }}]"
                            id="{{ $subChild->id }}"
                            data-parent="{{ $menu->id }}"
                            x-cloak
                            :aria-expanded="openItems[{{ $menu->id }}]"
                            x-collapse.duration.300ms
                            @foreach ($columns as $key => $column )
                                @if ($key === 'title')
                                    <td style="--col-width: {{ $column['width'] }};" class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                                <button class="ml-8 p-1 transition duration-200 cursor-pointer text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 {{ $subChild->children->count() > 0 ? 'visible' : 'invisible size-5.5' }}" 
                                                    @if($subChild->children->count() > 0)
                                                        @click="toggleItem({{ $subChild->id }})"
                                                    @endif
                                                    >
                                                    @if($subChild->children->count() > 0)
                                                        <x-heroicon-s-chevron-right class="size-3.5 transition-transform duration-200" ::class="{ 'rotate-90': openItems[{{ $subChild->id }}] }"/>
                                                    @endif
                                                </button>
                                            <span class="dark:text-gray-200">{{ $subChild->title }}</span>
                                        </div>
                                    </td>
                                @elseif ($key === 'action')
                                    <td style="--col-width: {{ $column['width'] }};" class="px-4 py-3">
                                        <button class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-2" wire:click="edit({{ $subChild->id }})">
                                            {{ __('Edit') }}
                                        </button>
                                        <button class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" wire:click="delete({{ $subChild->id }})">
                                            {{ __('Delete') }}
                                        </button>
                                    </td>
                                @else
                                    <td style="--col-width: {{ $column['width'] }};" class="px-4 py-3 text-left text-sm w-[var(--col-width)]">
                                        @if ($key === 'permission_name')
                                            @php
                                                $permissions = explode(' ',$subChild->permission_name);
                                            @endphp
                                            <ul class="flex flex-wrap items-center gap-1.5 *:data-permission:inline-flex *:data-permission:items-center *:data-permission:text-xs *:data-permission:font-medium *:data-permission:px-2 *:data-permission:py-0.5 *:data-permission:rounded-full *:data-permission:bg-sky-100 *:data-permission:text-sky-800 dark:*:data-permission:bg-sky-900 dark:*:data-permission:text-sky-300">
                                                @if($permissions === [''])
                                                    <li class="text-amber-400">{{ __('No permission assigned') }}</li>
                                                @else
                                                    @foreach($permissions as $permission)
                                                        <li data-permission>
                                                            {{ $permission }}
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        @else
                                            {{ $subChild->$key }}
                                        @endif
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
            {{-- @foreach($menus as $menu)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800" id="{{ $menu->id }}">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                                <button class="p-1 transition duration-200 cursor-pointer text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 {{ $menu->children->count() > 0 ? 'visible' : 'invisible size-5.5' }}" 
                                    @if($menu->children->count() > 0)
                                        @click="toggleItem({{ $menu->id }})"
                                    @endif
                                    >
                                    @if($menu->children->count() > 0)
                                        <x-heroicon-s-chevron-right class="size-3.5 transition-transform duration-200" ::class="{ 'rotate-90': openItems[{{ $menu->id }}] }"/>
                                    @endif
                                </button>
                            <span class="dark:text-gray-200">{{ $menu->title }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 dark:text-gray-200 flex items-center gap-2">
                        <x-icon :name="$menu->icon" class="size-4.5 " />
                        {{ $menu->icon }}
                    </td>
                    <td class="px-4 py-3 dark:text-gray-200">{{ $menu->permission_name }}</td>
                    <td class="px-4 py-3">
                        <button class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-2" wire:click="edit({{ $menu->id }})">
                            {{ __('Edit') }}
                        </button>
                        <button class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" wire:click="delete({{ $menu->id }})">
                            {{ __('Delete') }}
                        </button>
                    </td>
                </tr>
                @foreach($menu->children as $child)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800" 
                        x-show="openItems[{{ $menu->id }}]"
                        id="{{ $child->id }}"
                        data-parent="{{ $menu->id }}"
                        x-cloak
                        :aria-expanded="openItems[{{ $menu->id }}]"
                        x-collapse.duration.300ms>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <button class="ml-4 p-1 transition duration-200 cursor-pointer text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 {{ $child->children->count() > 0 ? 'visible' : 'invisible size-5.5' }}" 
                                    @if($child->children->count() > 0)
                                        @click="toggleItem({{ $child->id }})"
                                    @endif
                                    >
                                    @if($child->children->count() > 0)
                                        <x-heroicon-s-chevron-right class="size-3.5 transition-transform duration-200" ::class="{ 'rotate-90': openItems[{{ $child->id }}] }"/>
                                    @endif
                                </button>
                                <span class="dark:text-gray-200">{{ $child->title }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 dark:text-gray-200 flex items-center gap-2">
                            @if ($child->icon)
                                <x-icon :name="$child->icon" class="size-4.5 " />
                            @endif
                            {{ $child->icon }}
                        </td>
                        <td class="px-4 py-3 dark:text-gray-200">{{ $child->permission_name }}</td>
                        <td class="px-4 py-3">
                            <button class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-2" wire:click="edit({{ $child->id }})">
                                {{ __('Edit') }}
                            </button>
                            <button class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" wire:click="delete({{ $child->id }})">
                                {{ __('Delete') }}
                            </button>
                        </td>
                    </tr>
                    @if ($child->children->count() > 0)
                        @foreach($child->children as $subChild)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800" 
                                x-show="openItems[{{ $menu->id }}] && openItems[{{ $child->id }}]"
                                id="{{ $subChild->id }}"
                                data-parent="{{ $child->id }}"
                                x-cloak
                                :aria-expanded="openItems[{{ $child->id }}]"
                                x-collapse.duration.300ms>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <button class="ml-8 p-1 transition duration-200 cursor-pointer text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 {{ $subChild->children->count() > 0 ? 'visible' : 'invisible size-5.5' }}" 
                                            @if($subChild->children->count() > 0)
                                                @click="toggleItem({{ $subChild->id }})"
                                            @endif
                                            >
                                            @if($subChild->children->count() > 0)
                                                <x-heroicon-s-chevron-right class="size-3.5 transition-transform duration-200" ::class="{ 'rotate-90': openItems[{{ $subChild->id }}] }"/>
                                            @endif
                                        </button>
                                        <span class="dark:text-gray-200">{{ $subChild->title }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 dark:text-gray-200 flex items-center gap-2">
                                    @if ($subChild->icon)
                                        <x-icon :name="$subChild->icon" class="size-4.5" />
                                    @endif
                                    {{ $subChild->icon }}
                                </td>
                                <td class="px-4 py-3 dark:text-gray-200">{{ $subChild->permission_name }}</td>
                                <td class="px-4 py-3">
                                    <button class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-2" wire:click="edit({{ $subChild->id }})">
                                        {{ __('Edit') }}
                                    </button>
                                    <button class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" wire:click="delete({{ $subChild->id }})">
                                        {{ __('Delete') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endforeach --}}
                
        </tbody>
    </table>
    <div class="mt-4">
        {{ $menus->links() }}
    </div>
</div>
</div>