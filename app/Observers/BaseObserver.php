<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

abstract class BaseObserver
{
    abstract protected function getCacheKey(): string;

    private function clearCache(Model $setting): void
    {
        Cache::forget($this->getCacheKey() . '.' . $setting->key);
    }

    public function created(Model $model): void
    {
        $this->clearCache($model);
    }

    public function updated(Model $model): void
    {
        $this->clearCache($model);
    }

    public function saved(Model $model): void
    {
        $this->clearCache($model);
    }

    public function deleted(Model $model): void
    {
        $this->clearCache($model);
    }

    public function restored(Model $model): void
    {
        $this->clearCache($model);
    }
}
