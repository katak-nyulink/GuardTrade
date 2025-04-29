<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">
                        {{ str_replace('_',' ',$header) }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                @include('components.tree-table-row', ['item' => $item, 'level' => 0,'columns' => $headers])
            @endforeach
        </tbody>
    </table>
</div>
