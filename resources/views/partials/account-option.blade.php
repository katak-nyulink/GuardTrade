{{-- <optgroup label="{{ $account->name }}" name="{{ $setting->value }}">  --}}
    <option value="{{ $account->id }}" {{ $setting->value == $account->id ? 'selected' : '' }}>
        {{ str_repeat('—', $level) }} {{ $account->code }} - {{ $account->name }}
    </option>
{{-- </optgroup>     --}}
    @if($account->children && count($account->children) > 0)
        @foreach($account->children as $child)
            @include('partials.account-option', [
                'account' => $child,
                'setting' => $setting,
                'level' => $level + 1
            ])
        @endforeach
    @endif
