@props(['menu'])

<li class="dd-item" data-id="{{ $menu->id }}">
    <div class="dd-handle">
        <span>{{ $menu->title }}</span>
        <div class="space-x-1 text-sm">
            <button wire:click="$emit('editMenu', {{ $menu->id }})" class="text-blue-600">Edit</button>
            <button wire:click="$emit('deleteMenu', {{ $menu->id }})" class="text-red-600">Delete</button>
        </div>
    </div>
    @if ($menu->children && $menu->children->count())
        <ol class="dd-list">
            @foreach ($menu->children as $child)
                <x-build-menu-nestable :menu="$child" />
            @endforeach
        </ol>
    @endif
</li>