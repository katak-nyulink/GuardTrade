@props([
    'label'=>'',
    'icon' => null,
    'haveHeader' => false,
    'link'  => null,
    'data' => null,
])

@if ($haveHeader)
    <li class="text-xs text-gray-600 ml-10 my-2">
        <span class="uppercase">
            {{ $label }}
        </span>
    </li>
@else
    <!-- start::Submenu link -->
    <li class="{{ $haveHeader ? 'pl-16' : 'pl-10' }} pr-6 py-2 cursor-pointer hover:bg-black/30 transition duration-200 hover:text-gray-100">
        <a  href="{{ $link ?? '#' }}" class="flex items-center">
            @if ($icon)
                <x-dynamic-component :component="$icon" class="size-3.5 transition duration-200" :class=" linkHover || linkActive ? 'text-gray-100' : ''"/>
            @else
                {{-- <span class="mr-2 text-sm">&bull;</span> --}}
                <x-far-dot-circle class="size-3.5 mr-2"/>
            @endif
            <span class="overflow-ellipsis">{{ $label }}</span>
        </a>
    </li>
    <!-- end::Submenu link -->
@endif