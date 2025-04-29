<x-sidebar.backdrop/>
<aside x-bind="sidebar" class="sidebar sidebar-mini border-r border-slate-200 dark:border-slate-700 will-change-[transform,width] shadow-[10px_0px_15px_-3px_rgba(0,_0,_0,_0.1)] dark:shadow-white/3">
    <!-- Header dan bagian lainnya tetap sama -->
    <!-- Logo Area -->
    <a href="/" class="sidebar-header">
        <x-app-logo-icon class="size-6 flex-none transition"/>
        <span x-show="!isCollapsed" x-transition class="text-lg text-nowrap ml-2 text-slate-100">
            {{ config('app.name') }}
        </span>
    </a>
     
    <nav class="flex-1 custom-scrollbar overflow-y-auto px-2.5 py-4">
        <ul class="space-y-2">
            @foreach ($groupedMenu as $group => $menus)
                @if ($group)
                    <div x-show="!isCollapsed" x-cloak x-transition class="uppercase text-xs text-slate-400 px-3 py-2 mt-8 first:mt-0">{{ $group }}</div>
                @endif

                @foreach ($menus as $menu)
                    @if ($menu->type === 'dropdown')
                        <li x-data="sidebarDropdown({{ Route::has($menu->route) && request()->routeIs($menu->route.'*') ? 'true' : 'false' }})">
                            <button x-bind="trigger" x-ref="trigger" class="sidebar-navlink" >
                                <x-icon name="{{ $menu->icon }}" class="flex-none w-5 h-5" />
                                <div class="w-full flex items-center justify-between gap-2" x-show="!isCollapsed" x-cloak x-transition>
                                    <span class="flex-none">{{ $menu->title }}</span>
                                    <x-icon name="chevron-right" ::class="{'rotate-90': open}" class="w-4 h-4 transition-transform duration-300" />
                                </div>
                                {{-- <span x-show="isCollapsed" class="sidebar-tooltip">{{ $menu->title }}</span> --}}
                            </button>

                            <template x-teleport="aside">
                                <div x-bind="popOver"
                                    class="absolute z-[9999]"
                                    x-effect="
                                        const arrow = document.createElement('div');
                                        arrow.className = 'absolute w-2.5 h-2.5 bg-white dark:bg-gray-800 transform rotate-45';
                                        
                                        const existingArrow = $el.querySelector('.arrow-pseudo');
                                        if (existingArrow) existingArrow.remove();
                                        
                                        arrow.classList.add('arrow-pseudo');
                                        const placement = $el.dataset.placement;
                                        
                                        if (placement === 'right-start') {
                                            arrow.style.left = '-5px';
                                            arrow.style.top = '15px';
                                        }
                                        
                                        $el.appendChild(arrow);
                                    ">
                                    <ul class="bg-white dark:bg-gray-800 dark:text-gray-100 rounded-lg shadow-lg w-64 ring-1 ring-slate-200 dark:ring-slate-700 max-h-[calc(100vh_-_100px)] overflow-y-auto data-list:first:rounded-t-lg data-list:last:rounded-b-lg ">
                                        <li class="uppercase text-xs text-slate-600 dark:text-slate-400 px-4 py-3 border-b">{{ $menu->title }}</li>
                                        @foreach ($menu->children as $child)
                                            @if ($child->type === 'section')
                                                <li x-show="!showAsPopup || isCollapsed" x-cloak class="uppercase text-xs text-slate-400 px-4 py-3">{{ $child->title }}</li>
                                                @foreach ($child->children as $sectionLink)
                                                    @can($sectionLink->permission_name)
                                                        <li data-list>
                                                            <a wire:navigate data-menu href="{{ Route::has($sectionLink->route) ? route($sectionLink->route) : '#' }}" 
                                                                class="sidebar-navlink as-popup {{ Route::has($sectionLink->route) && request()->routeIs($sectionLink->route) ? 'active' : '' }}">
                                                                {{-- <span class="flex-none text-sm">•</span> --}}
                                                                @if ($sectionLink->icon)
                                                                    <x-icon name="{{ $sectionLink->icon }}" class="size-5 flex-none" />
                                                                @else
                                                                    <x-far-circle class="size-3.5 flex-none"/>
                                                                @endif
                                                                <span x-show="!showAsPopup || isCollapsed" x-cloak >{{ $sectionLink->title }}</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                @endforeach
                                            @else
                                                @can($child->permission_name)
                                                    <li data-list>
                                                        <a wire:navigate data-menu href="{{ Route::has($child->route) ? route($child->route) : '#' }}" 
                                                            class="sidebar-navlink as-popup {{ Route::has($child->route) && request()->routeIs($child->route) ? 'active' : '' }}">
                                                            {{-- <span class="flex-none text-sm">•</span> --}}
                                                            @if ($child->icon)
                                                                <x-icon name="{{ $child->icon }}" class="size-5 flex-none" />
                                                            @else
                                                                <x-far-circle class="size-3.5 flex-none"/>
                                                            @endif
                                                            <span x-show="!showAsPopup || isCollapsed" x-cloak >{{ $child->title }}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </template>

                            <div x-show="open && !showAsPopup" x-cloak x-collapse.duration.300ms class="ml-6">
                                <ul class="space-y-1 *:first:mt-1">
                                    @foreach ($menu->children as $child)
                                        @if ($child->type === 'section')
                                            <li x-show="!showAsPopup || isCollapsed" x-cloak  class="uppercase text-xs text-slate-500 px-4 py-3">{{ $child->title }}</li>
                                            @foreach ($child->children as $sectionLink)
                                                @can($sectionLink->permission_name)
                                                    <li>
                                                        <a wire:navigate href="{{ Route::has($sectionLink->route) ? route($sectionLink->route) : '#' }}" 
                                                            class="sidebar-navlink {{ Route::has($sectionLink->route) && request()->routeIs($sectionLink->route) ? 'active' : '' }}">
                                                            {{-- <span class="flex-none text-sm">•</span> --}}
                                                            @if ($sectionLink->icon)
                                                                <x-icon name="{{ $sectionLink->icon }}" class="size-5 flex-none" />
                                                            @else
                                                                <x-far-circle class="size-3.5 flex-none"/>
                                                            @endif
                                                            <span x-show="!showAsPopup || isCollapsed" x-cloak >{{ $sectionLink->title }}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            @endforeach
                                        @else
                                            @can($child->permission_name)
                                                <li>
                                                    <a wire:navigate href="{{ Route::has($child->route) ? route($child->route) : '#' }}" 
                                                        class="sidebar-navlink {{ Route::has($child->route) && request()->routeIs($child->route) ? 'active' : '' }}">
                                                        {{-- <span class="flex-none text-sm">•</span> --}}
                                                        @if ($child->icon)
                                                            <x-icon name="{{ $child->icon }}" class="size-5 flex-none" />
                                                        @else
                                                            <x-far-circle class="size-3.5 flex-none"/>
                                                        @endif
                                                        <span x-show="!showAsPopup || isCollapsed" x-cloak >{{ $child->title }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @else
                        <li>
                            <a wire:navigate href="{{ Route::has($menu->route) ? route($menu->route) : '#' }}" 
                                class="sidebar-navlink {{ Route::has($menu->route) && request()->routeIs($menu->route) ? 'active' : '' }}"
                                >
                                {{-- <x-icon name="{{ $menu->icon }}" class="w-5 h-5 flex-none" /> --}}
                                @if ($menu->icon)
                                    <x-icon name="{{ $menu->icon }}" class="size-5 flex-none" />
                                @else
                                    <x-far-circle class="size-5 flex-none"/>
                                @endif
                                <span class="truncate">{{ $menu->title }}</span>
                                {{-- <span x-show="isCollapsed" class="sidebar-tooltip">{{ $menu->title }}</span> --}}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endforeach
        </ul>
    </nav>
</aside>