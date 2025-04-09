@props([
    'label'=>'',
    'icon' => null,
    'haveHeader' => false,
    'link'  => null,
    'data' => null,
    'badge' => null,
    'badgeEnd' => false,
])

@php
    $isActive = request()->url() === $link;
@endphp

@if ($haveHeader)
    <li class="text-xs text-gray-600 ml-10 my-2">
        <span class="uppercase">
            {{ $label }}
        </span>
    </li>
@else
    <!-- start::Submenu link -->
    <li class="{{ $haveHeader ? 'pl-16' : 'pl-10' }} pr-6 py-2 cursor-pointer hover:bg-black/30 transition duration-200 hover:text-gray-100 {{ $isActive ? 'bg-black/30 text-gray-100' : '' }}">
        <a href="{{ $link ?? '#' }}" class="flex items-center">
            @if ($icon)
                <x-dynamic-component :component="$icon" class="size-3.5"/>
            @else
                <x-far-dot-circle class="size-3.5 mr-2"/>
            @endif
            <span class="overflow-ellipsis">{{ $label }}</span>
            @if($badge)
                <div @class(['flex justify-end flex-1' => $badgeEnd])>
                    <x-badge :label="$badge" blue outline {{ $attributes->twMergeFor('badge', 'rounded-full') }} />
                </div>
            @endif
        </a>
    </li>
    <!-- end::Submenu link -->
@endif