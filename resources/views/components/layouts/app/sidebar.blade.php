<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('partials.head')
    <body x-data="{ 
        ...appearance(),
        ...sidebar(), 
        stickyHeader: true,
        }" class="custom-scrollbar" 
        x-on:keydown.window.escape="window.innerWidth < 1024 ? isCollapsed = true : isCollapsed = false">
        <x-sidebar/>
        <div class="transition-all duration-300 will-change-auto" x-bind="mainContent">
            <x-header/>
            {{ $slot }}
        </div>
        @livewireScripts

        @stack('other-scripts')
        @stack('scripts')
    </body>
</html>
