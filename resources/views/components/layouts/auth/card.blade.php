<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="auth" x-data="{ ...appearance() }">
    <div class="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-md flex-col gap-6">
            <a href="/" class="flex items-center justify-center gap-2 font-medium" wire:navigate>
                {{-- <span class="flex h-9 w-9 items-center justify-center rounded-md"> --}}
                    <x-app-logo classLogo="size-16 " classText="text-3xl tracking-wider dark:text-white" heading="h1"/>
                {{-- </span> --}}

                <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <div class="flex flex-col gap-6">
                <x-card class="max-w-md dark:bg-gray-900/80 overflow-hidden">
                    {{ $slot }}
                </x-card>
            </div>
        </div>
    </div>
    @livewireScripts
</body>

</html>