<tr class="hover:bg-gray-50">
    @foreach($columns as $column)
        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200" @if($loop->first) style="padding-left: {{ $level * 20 + 24 }}px" @endif>
            @if($loop->first && $item->children->count() > 0)
                <button 
                    class="mr-2 transform transition-transform duration-200"
                    onclick="toggleChildren({{ $item->id }})"
                    id="btn-{{ $item->id }}"
                >
                    <x-icon name="chevron-right" class="w-4 h-4" />
                </button>
            @endif
            {{ $item->{$column} }}
        </td>
    @endforeach
</tr>

@if($item->children->count() > 0)
    <template id="children-{{ $item->id }}">
        @foreach($item->children as $child)
            @include('components.tree-table-row', [
                'item' => $child, 
                'level' => $level + 1,
                'columns' => $columns
            ])
        @endforeach
    </template>
@endif

@once
    @push('scripts')
    <script>
        function toggleChildren(id) {
            const btn = document.getElementById(`btn-${id}`);
            const template = document.getElementById(`children-${id}`);
            const isExpanded = btn.classList.contains('rotate-90');
            
            if (isExpanded) {
                const rows = document.querySelectorAll(`[data-parent="${id}"]`);
                rows.forEach(row => row.remove());
                btn.classList.remove('rotate-90');
            } else {
                const clone = template.content.cloneNode(true);
                const rows = Array.from(clone.children);
                rows.forEach(row => {
                    row.dataset.parent = id;
                    template.parentElement.insertBefore(row, template);
                });
                btn.classList.add('rotate-90');
            }
        }
    </script>
    @endpush
@endonce
