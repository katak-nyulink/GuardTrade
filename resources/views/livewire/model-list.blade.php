<div>
    <div class="mb-4 flex justify-between">
        <div>
            <input type="text" wire:model.live="search" placeholder="Search..." class="rounded border px-4 py-2">
        </div>
        <div>
            <a href="{{ route('models.create') }}" class="rounded bg-blue-500 px-4 py-2 text-white">Create New</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr>
                    @foreach($columns as $column)
                    <th class="px-4 py-2">
                        <button wire:click="sortBy('{{ $column }}')" class="text-left">
                            {{ ucwords(str_replace('_', ' ', $column)) }}
                            @if ($sortField === $column)
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </button>
                    </th>
                    @endforeach
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        @foreach($columns as $column)
                            <td class="border px-4 py-2">{{ $item->$column }}</td>
                        @endforeach
                        <td class="border px-4 py-2">
                            <a href="{{ route('models.edit', $item) }}" class="text-blue-500">Edit</a>
                            <button wire:click="delete({{ $item->id }})" class="text-red-500 ml-2">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
