<x-layouts.app.sidebar  :title="$title ?? null">
    <main class="min-h-screen p-6">
        <section class="content-header flex flex-wrap mb-4">
            <div class="w-full sm:max-w-1/2 sm:flex-[0_0_50%]">
                <h1 class="text-xl sm:text-2xl">Judul</h1>
            </div>
            <livewire:breadcrumbs/>
        </section>
        <section class="content">

            <ul>
                {{-- {{ \Cache::forget('settings.values') }} --}}
                {{-- @forelse (app('setting')->getAllSettings() as $setting)
                    <li>{{ $setting->key }} - {{ $setting->value }}</li>
                @empty
                    <li>kosong</li>
                @endforelse --}}
            </ul>
            {{ $slot }}
        </section>
    </main> 
</x-layouts.app.sidebar>