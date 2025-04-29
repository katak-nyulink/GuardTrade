@props(['item', 'level' => 0, 'isActive' => false])

<li x-data="{
    open: false,
    hasChildren: {{ isset($item['subitems']) ? 'true' : 'false' }},
    isActive: {{ $component->isActive($item) ? 'true' : 'false' }}
}">
    @if(isset($item['subitems']))
        <!-- Submenu item -->
        <button 
            @click="open = !open"
            class="group flex w-full items-center rounded-lg p-2 text-gray-300 hover:bg-gray-700 pl-{{ $level * 2 }}"
            :class="{ 'bg-gray-900': isActive }"
        >
            <span class="flex-1 text-left truncate">{{ $item['label'] }}</span>
            <x-heroicon-o-chevron-right x-show="hasChildren" class="ml-2 h-4 w-4" />
            {{-- <x-heroicon-o-chevron-down x-show="!open" class="ml-2 h-4 w-4" />
            <x-heroicon-o-chevron-up x-show="open" class="ml-2 h-4 w-4" /> --}}
        </button>
        
        <!-- Nested submenu -->
        <ul 
            x-show="open"
            x-collapse
            {{-- style="--margin-left: calc({{ ($level + 1) * 2 }}rem;" --}}
            class="ml-[calc(var(--spacing)_*_{{ ($level + 1) * 2 }})] space-y-1 border-l border-gray-700"
        >
            @foreach($item['subitems'] as $subitem)
                @include('components.sidebar.item', [
                    'item' => $subitem, 
                    'level' => $level + 1,
                    'isActive' => $component->isActive($subitem)])
            @endforeach
        </ul>
    @else
        <!-- Leaf item -->
        <a 
            href="{{ $item['url'] }}"
            style="--padding-left: {{ $level * 2 }}rem;"
            class="flex items-center rounded-lg p-2 text-gray-300 hover:bg-gray-700 truncate pl-(--padding-left)"
            :class="{ 'bg-gray-900': isActive }"
        >
            {{ $item['label'] }}
        </a>
    @endif
</li>