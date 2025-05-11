@props(['classLogo' => 'size-5', 'classText' => 'text-sm','title' => app('setting')->get('app_name'),'heading'=>'h5'])

<div class="flex aspect-square items-center justify-center rounded-md bg-accent-content text-accent-foreground">
    @empty(app('setting')->get('app_logo'))
        <x-app-logo-icon @class(['fill-current', $classLogo]) />
    {{-- @if (app('setting')->get('app_logo') && app('setting')->get('app_logo') !== 'default') --}}
    @else
        <img src="{{ asset('storage/' . app('setting')->get('app_logo')) }}" alt="{{ app('setting')->get('app_name') }}" class="w-8 h-8" />
    @endempty
</div>
<div class="ml-2 text-left">
    <{{ $heading }} @class(['mb-0.5 truncate font-semibold', $classText])>{{ $title }}</{{ $heading }}>
</div>
