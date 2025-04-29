@props([
    'title'=>'',
    'icon' => null,
    'linkActive' => null,
    'linkHover' => false,
    'routes' => [],
    'badge' => null,
    'badgeEnd' => false,
])
 
@php
    $currentRoute = request()->route()->getName();
    $linkActive = in_array($currentRoute, (array)$routes);
@endphp

<div x-data="{ linkHover: @js($linkHover), linkActive: @js($linkActive) }">
    <div 
        x-on:mouseover = "linkHover = true"
        x-on:mouseleave = "linkHover = false"
        x-on:click = "linkActive = !linkActive"
        class="group flex items-center justify-between px-6 py-3 cursor-pointer transition duration-200"
        :class=" linkActive ? 'bg-black/50 text-gray-100' : 'text-gray-400 hover:text-gray-100 hover:bg-black/30'"
    >
        <div class="flex items-center">
            @php
                $icon = 'heroicon-o-' . $icon;
                $icon = str_replace(' ', '-', $icon);
            @endphp
            {{-- <x-heroicon-o-{{ $icon }} class="w-5 h-5 transition duration-200" ::class=" linkHover || linkActive ? 'text-gray-100' : ''"/> --}}
            <x-dynamic-component :component="$icon" class="w-5 h-5 flex-none transition duration-200" ::class=" linkHover || linkActive ? 'text-gray-100' : ''"/>
            <span class="ml-3 truncate">{{ $title }}</span>
            @if($badge)
                <div @class(['flex justify-end flex-1' => $badgeEnd])>
                    <x-badge :label="$badge" blue outline {{ $attributes->twMergeFor('badge', 'rounded-full') }} />
                </div>
            @endif
        </div>
        <x-heroicon-s-chevron-right class="w-3 h-3 transition duration-300" x-bind:class="{'rotate-90':linkActive}"/>
    </div>
    <!-- start::Submenu -->
    <ul x-show="linkActive" x-cloak x-collapse.duration.300ms class="text-gray-400">
        {{ $slot }}
    </ul>
    <!-- end::Submenu -->
</div>