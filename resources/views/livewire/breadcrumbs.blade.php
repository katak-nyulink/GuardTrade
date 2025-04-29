<div class="w-full sm:max-w-1/2 sm:flex-[0_0_50%]">
    <x-breadcrumb class="sm:ml-auto">
        <x-breadcrumb-item label="Home" :href="route('home')" icon="home" class:icon="size-4"/>
        @foreach($breadcrumbs as $crumb)
            @if($loop->last)
                <x-breadcrumb-item :label="$crumb['name']" class:icon="size-4"/>
            @else
                <x-breadcrumb-item :label="$crumb['name']" :href="$crumb['route']" class:icon="size-4"/>
            @endif
        @endforeach
    </x-breadcrumb>
</div>