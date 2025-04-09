@props(['classLogo' => 'size-5', 'classText' => 'text-sm','title' => config('app.name'),'heading'=>'h5'])

<div class="flex aspect-square items-center justify-center rounded-md bg-accent-content text-accent-foreground">
    <x-app-logo-icon @class(['fill-current', $classLogo]) />
</div>
<div class="ml-1 text-left">
    <{{ $heading }} @class(['mb-0.5 truncate font-semibold', $classText])>{{ $title }}</{{ $heading }}>
</div>
