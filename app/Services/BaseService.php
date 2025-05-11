<?php

namespace App\Services;

abstract class BaseService
{
    protected int $cacheDuration = 3600; // 1 hours
    protected ?string $subKey = null;

    abstract protected function getCacheKey(): string;

    public function clearCache(?string $subKey = null)
    {
        $finalCacheKey = $subKey ? $this->getCacheKey() . '.' . $subKey : $this->getCacheKey();
        cache()->forget($finalCacheKey);
    }
}
