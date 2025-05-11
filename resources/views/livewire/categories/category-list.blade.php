<div>
    <x-table searchable class="bg-white dark:bg-slate-800 overflow-hidden">
        <x-slot:heading>
            @foreach ($this->columns as $key => $column)
                <x-th  :sortable="$column['sortable']" :sortBy="$key" :$sortAsc>
                    <span>{{ $column['label'] }}</span>
                </x-th>
            @endforeach
        </x-slot:heading>

        @forelse ($this->categories as $category)
            <x-tr x-data="{ open: true }" ::class="{'border-b-0': open && $category->children_count}">
                <x-td>
                    <div class="flex items-center">
                        @if ($category->children_count > 0)
                            <button @click="open = !open" class="mr-2">
                                <svg class="w-4 h-4 text-gray-400 transition-transform" 
                                        :class="{'rotate-90': open}"
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
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
        @empty
            <x-not-found />
        @endforelse

        <x-slot:bulkActions>
            <h2 class="text-lg font-medium text-gray-800 sm:text-xl dark:text-gray-200">{{ __('crud.categories.description') }}</h2>
        </x-slot:bulkActions>
    </x-table>
</div>
