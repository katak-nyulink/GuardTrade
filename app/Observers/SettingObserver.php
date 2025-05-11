<?php

namespace App\Observers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingObserver extends BaseObserver
{
    protected function getCacheKey(): string
    {
        return 'setting';
    }

    private function clearCache(Setting $setting): void
    {
        Cache::forget($this->getCacheKey() . '.values');
    }
}
