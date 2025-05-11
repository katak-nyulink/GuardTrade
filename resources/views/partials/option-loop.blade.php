@props(['setting', 'item','withCode' => false,'level' => 0])
{{-- @dd($item) --}}

<option value="{{ $item->id }}" {{ $setting->value == $item->id ? 'selected' : '' }}>
    <div class="flex items-center w-full"><span class="ml-6">@if ($withCode) {{ $item->code }} -  @endif </span></span>{{ $item->name}}</div>
</option>
@if ($item->children_count > 0)
    @foreach ($item->children as $child)
        @include('partials.option-loop', ['setting' => $setting, 'item' => $child, 'withCode' => $withCode, 'level' => $level + 1])
    @endforeach
@endif