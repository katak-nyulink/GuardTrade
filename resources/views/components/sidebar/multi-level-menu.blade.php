@props(['menuItems', 'level' => 0,'popOver'=>''])

<ul  @class([
    'menu-level-0 space-y-1' => $level === 0,
    'menu-level-1 space-y-1 first:mt-1' => $level === 1 && !$popOver,
    'submenu space-y-1 first:mt-1' => $level > 0 && !$popOver,
    '*:data-list:first:*:rounded-t-lg *:data-list:last:*:rounded-b-lg' => $popOver === true
])>
    @foreach ($menuItems as  $item)
        @can($item->permission_name)
            @if(isset($item->children) && $item->children->isNotEmpty())
                @if (is_object($item) && $item->type == 'dropdown')
                @php
                    $isActive = $item->children->contains(function($child) {
                        return Route::has($child->route) && request()->routeIs($child->route);
                    });
                    // $isActive = $isActive ? 'true' : 'false';
                @endphp
                    <li data-list @class([
                        'menu-item',
                        'has-children relative' => $item->children->isNotEmpty(),
                        'menu-item-level-' . $level])
                        x-data="sidebarDropdown({{ $isActive ? 'true' : 'false' }})">
                        <button x-bind="trigger" x-ref="trigger" :class="{'active': {{ $isActive ? 'true' : 'false' }} || open}" @class([
                            'sidebar-navlink',
                            'as-popup'=> $popOver === true,
                            ])
                            >
                            @if($item->icon)
                                <x-icon name="{{ $item->icon }}" class="flex-none size-4" />
                            @endif
                            <div class="w-full flex items-center justify-between gap-2" x-show="!isCollapsed || showAsPopup" x-cloak x-transition.duration.300ms>
                                <span class="flex-none">{{ $item->title }}</span>
                                <x-icon name="chevron-right" ::class="{'rotate-90': open && !showAsPopup}" class="w-4 h-4 transition-transform duration-300" />
                            </div>
                        </button>

                        <template x-teleport="aside">
                            <div x-bind="popOver" class="z-[9999] bg-white dark:bg-gray-800 dark:text-gray-100 rounded-lg shadow-lg w-64 ring-1 ring-slate-200 dark:ring-slate-700 max-h-[calc(100vh_-_100px)]">
                                <x-sidebar.multi-level-menu :menuItems="$item->children" :level="$level + 1" :popOver="true"/>
                            </div>
                        </template>

                        <div x-show="open && !showAsPopup" x-cloak x-collapse.duration.300ms class="ml-6">
                            <x-sidebar.multi-level-menu :menuItems="$item->children" :level="$level + 1" />
                        </div>
                    </li>
                @elseif (is_object($item) && $item->type === 'section')
                    <li x-show="!showAsPopup || isCollapsed" x-cloak class="uppercase text-xs text-slate-400 px-4 py-3">{{ $item->title }}</li>
                    <x-sidebar.multi-level-menu :menuItems="$item->children" :level="$level + 1" />
                @endif
            @else 
                <li data-list>
                    <a wire:navigate href="{{ Route::has($item->route) ? route($item->route) : '#' }}" 
                        {{-- x-bind:class="{
                            'justify-center': isCollapsed || (typeof showAsPopup !== 'undefined' && showAsPopup)
                        }" --}}
                        @class([
                            'sidebar-navlink',
                            'as-popup'=> $popOver === true,
                            'active'=> Route::has($item->route) && request()->routeIs($item->route)
                        ])>
                        @if ($item->icon)
                            <x-icon name="{{ $item->icon }}" class="size-4 flex-none" />
                            {{-- <x-dynamic-component :component="$item->icon" class="size-5 flex-none"/> --}}
                        @else
                            <x-far-circle class="size-3 flex-none"/>
                        @endif
                        <span class="truncate" x-show="(typeof showAsPopup !== 'undefined' && showAsPopup) || !isCollapsed" x-cloak x-transition.duration.300ms>{{ $item->title }}</span>
                    </a>
                </li>
            @endif
        @endcan
    @endforeach
</ul>