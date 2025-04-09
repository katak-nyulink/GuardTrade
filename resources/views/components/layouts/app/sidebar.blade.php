<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('partials.head')
    <body x-data="{ sidebarOpen: false, stickyHeader: true }" class="custom-scrollbar" 
     x-init="$watch('sidebarOpen', value => { if (value) { document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); } })" 
     @keydown.window.escape="sidebarOpen = false"
    >
        <x-sidebar/>
        <div class="lg:pl-64 w-full flex flex-col min-h-dvh">
            <x-header/>
            <div class="min-h-screen bg-slate-100 dark:bg-slate-800 p-8">
                {{ $slot }}
            </div>
        </div>
        @livewireScripts
    </body>
</html>
