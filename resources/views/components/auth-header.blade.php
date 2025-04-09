@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <h2 class="text-xl font-semibold sm:text-2xl dark:text-gray-200">{{ $title }}</h2>
    <h5 class="text-sm text-gray-500 sm:text-base dark:text-gray-400">{{ $description }}</h5>
</div>
