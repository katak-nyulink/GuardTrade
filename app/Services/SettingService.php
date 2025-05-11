<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SettingService extends BaseService
{
    protected function getCacheKey(): string
    {
        return 'setting';
    }

    /**
     * Get all settings, optionally filtered by group
     *
     * @param string|null $group
     * @return \Illuminate\Support\Collection
     */
    public function getAllSettings(?string $group = null)
    {
        Cache::forget($this->getCacheKey() . '.values');
        $cacheKey = $this->getCacheKey() . '.values';

        $settings = Cache::remember($cacheKey, $this->cacheDuration, function () {
            return Setting::withCount('group')->get();
            // return Setting::get()->map(function ($setting) {
            //     return (object)[
            //         'key' => $setting->key,
            //         'value' => $setting->value,
            //         'group' => $setting->group
            //     ];
            // });
        });

        if ($group) {
            return $settings->where('group', $group);
        }

        return $settings;
    }

    /**
     * Get setting with cache support
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->getAllSettings();
        return $settings->firstWhere('key', $key)?->value ?? $default;
    }

    /**
     * Set setting value with cache management
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $group
     * @return Setting
     */
    public function set(string $key, $value, ?string $group = null): Setting
    {
        // Cache::forget($this->getCacheKey() . '.values');
        return Setting::setValue($key, $value, $group);
    }

    public function ensureDefaults(): void
    {
        if (!$this->get('default_currency_code')) {
            $this->set('default_currency_code', 'USD', 'system');
        }
    }

    public function boot(): void
    {
        $this->ensureDefaults();
    }
}
