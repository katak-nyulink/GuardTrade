<div>
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="overflow-hidden border-b border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @foreach ($columns as $column)
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $column['label'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- @foreach ($items as $item)
                        <tr>
                            @foreach ($columns as $key => $column)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->$key }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        <button wire:click="addRow" class="px-4 py-2 bg-blue-500 text-white rounded">Add Row</button>
        <button wire:click="removeRow" class="px-4 py-2 bg-red-500 text-white rounded">Remove Row</button>
    </div>
</div>
