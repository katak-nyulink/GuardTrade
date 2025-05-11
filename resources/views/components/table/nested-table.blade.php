<div>
    <x-table>
        <x-slot:header>
            <x-th label="Name" />
            <x-th label="Slug" />
            <x-th label="Description" />
            <x-th label="Created At" />
            <x-th label="Actions" />
        </x-slot:header>

        @forelse ($categories as $category)
            <x-tr>
                <x-td>
                    <div class="flex items-center">
                        @if ($category->children_count > 0)
                            <button x-data="{ open: false }" @click="open = !open" class="flex items-center">
                                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16m-8-8v16"/>
                                </svg>
                            </button>
                        @endif
                        <span>{{ $category->name }}</span>
                    </div>
                    </x-td>
                <x-td :label="$category->slug"/>
                <x-td :label="$category->description"/>
                <x-td :label="$category->created_at->diffForHumans()" />
                <x-td>
                    <x-dropdown w-32>
                        <x-slot:trigger>
                            <x-button icon="ellipsis-vertical" flat gray xs class="px-1.5" />
                        </x-slot:trigger>

                        <x-dropdown-item label="View" icon="eye" />
                        <x-dropdown-item label="Edit" icon="pencil-square" />
                        <x-dropdown-item label="Delete" icon="trash" />
                    </x-dropdown>
                </x-td>
            </x-tr>
            @if($category->children_count > 0)
                <template x-if="open">
                    @foreach ($category->children as $children)
                        <x-tr class="bg-gray-50">
                            <x-td>
                                <div class="flex items-center pl-8">
                                    <span>{{ $children->name }}</span>
                                </div>
                            </x-td>
                            <x-td :label="$children->slug"/>
                            <x-td :label="$children->description"/>
                            <x-td :label="$children->created_at->diffForHumans()" />
                            <x-td>
                                <x-dropdown w-32>
                                    <x-slot:trigger>
                                        <x-button icon="ellipsis-vertical" flat gray xs class="px-1.5" />
                                    </x-slot:trigger>

                                    <x-dropdown-item label="View" icon="eye" />
                                    <x-dropdown-item label="Edit" icon="pencil-square" />
                                    <x-dropdown-item label="Delete" icon="trash" />
                                </x-dropdown>
                            </x-td>
                        </x-tr>
                    @endforeach
                </template>
            @endif
        @endforeach
        @empty
            <x-tr>
                <x-td colspan="5" class="text-center">
                    <span class="text-gray-500">No categories found.</span>
                </x-td>
            </x-tr>
        @endforelse
    </x-table>
</div>