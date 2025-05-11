<?php

namespace App\Livewire\Settings;

// use Livewire\Component;

use App\Models\Account;
use App\Models\Setting;
use App\Utils\PageComponent;
use Illuminate\Support\Facades\Cache;

class SettingManager extends PageComponent
{
    public $settings;
    public $accounting;
    public ?string $activeTab = 'general';

    public $newKey = '';
    public $newValue = '';
    public $newGroup = 'general';
    protected string $pathName = 'settings';

    public function mount()
    {
        $this->loadSettings();
        $this->accounting = Account::with('children.children.children.children.children')
            ->where('parent_id', null)
            ->orderBy('code')
            ->get();
    }

    public function loadSettings()
    {
        // Cache::forget('settings.values');
        $this->settings = app('setting')->getAllSettings();
        // $settings = Setting::all()->groupBy('group');
        // $this->settings = $settings->toArray();
    }

    public function updateSetting($key, $value)
    {
        // Setting::setValue($key, $value);
        app('setting')->set($key, $value);
        // $this->loadSettings();
        session()->flash('message', 'Setting updated successfully.');
        // $setting = Setting::find($key);
        // if ($setting) {
        //     $setting->value = $value;
        //     $setting->save();
        //     session()->flash('message', 'Setting updated successfully.');
        // }
    }

    public function addSetting()
    {
        $this->validate([
            'newKey' => 'required|unique:settings,key',
            'newValue' => 'required',
            'newGroup' => 'required'
        ]);
        app('setting')->set($this->newKey, $this->newValue, $this->newGroup);
        // Setting::setValue($this->newKey, $this->newValue, $this->newGroup);
        // Setting::create([
        //     'key' => $this->newKey,
        //     'value' => $this->newValue,
        //     'group' => $this->newGroup
        // ]);

        $this->newKey = '';
        $this->newValue = '';
        $this->newGroup = 'general';

        $this->loadSettings();
        session()->flash('message', 'Setting added successfully.');
    }

    // public function render()
    // {
    //     return view('livewire.settings.setting-manager');
    // }
}
