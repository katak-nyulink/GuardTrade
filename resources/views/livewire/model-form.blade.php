<div>
    <form wire:submit="save">
        <div class="space-y-4">
            @foreach($fields as $field)
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        {{ ucwords(str_replace('_', ' ', $field)) }}
                    </label>
                    <input type="text" 
                           wire:model="model.{{ $field }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error("model.$field")
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                {{ $isEdit ? 'Update' : 'Create' }}
            </button>
        </div>
    </form>
</div>
