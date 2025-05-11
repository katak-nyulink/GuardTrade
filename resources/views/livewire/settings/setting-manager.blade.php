<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <!-- Add New Setting Form -->
    <div class="card mb-4">
        <div class="card-header">Add New Setting</div>
        <div class="card-body">
            <form wire:submit.prevent="addSetting">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" wire:model="newKey" placeholder="Key">
                        @error('newKey') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" wire:model="newValue" placeholder="Value">
                        @error('newValue') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" wire:model="newGroup" placeholder="Group">
                        @error('newGroup') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Settings Groups -->
    @foreach($settings->groupBy('group') as $groupName => $groupSettings)
        <x-card class="card mb-4">
            <x-card-header :title="$groupName"/>
            <x-card-content class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Key</th>
                                <th>Value</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupSettings as $setting)
                                <tr>
                                    <td>{{ Str::headline($setting->key) }}</td>
                                    <td>
                                        @if (Str::of($setting->key)->contains('account_id'))
                                            <select class="form-control" wire:change="updateSetting('{{ $setting->key }}', $event.target.value)">
                                                <option value="">Select Account</option>
                                                @foreach ($accounting as $account)
                                                    <optgroup label="{{ $account->name }}" name="{{ $setting->value }}">
                                                        @include('partials.account-option', [
                                                            'account' => $account,
                                                            'setting' => $setting,
                                                            'level' => 0
                                                        ])
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        @elseif(Str::of($setting->key)->contains('inventory_accounting_method'))
                                            <select wire:change="updateSetting('{{ $setting->key }}', $event.target.value)">
                                                <option value="">Select Accounting Method</option>
                                                @foreach (\App\Enums\InventoryAccountingMethod::cases() as $method)
                                                    <option value="{{ $method->value }}" {{ $setting->value == $method->value ? 'selected' : '' }}>
                                                        {{ $method->value }} - {{ $method->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <x-input placeholder="{{ Str::headline($setting->key) }}" value="{{ $setting->value }}" wire:change="updateSetting('{{ $setting->key }}', $event.target.value)"/>
                                        
                                            {{-- <input type="text" 
                                                class="form-control" 
                                                value="{{ $setting->value }}"
                                                wire:change="updateSetting('{{ $setting->key }}', $event.target.value)">
                                            @error($setting->key) <span class="text-danger">{{ $message }}</span> @enderror --}}
                                        @endif

                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" 
                                                wire:click="deleteSetting('{{ $setting->key }}')"
                                                onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card-content>
        </x-card>
    @endforeach
</div>
