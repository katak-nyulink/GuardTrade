<?php

namespace App\Observers;

use App\Models\Menu;
use App\Services\MenuService;

class MenuObserver
{
    public function saved(Menu $menu)
    {
        app(MenuService::class)->clearCache();

        // If parent changed, clear cache for old parent too
        // If parent changed, clear cache for old parent too
        if ($menu->isDirty('parent_id')) {
            app(MenuService::class)->clearCache();
        }
    }

    public function deleted(Menu $menu)
    {
        app(MenuService::class)->clearCache();

        // If this menu had children, they were deleted (due to cascade)
        // So we need to clear cache to rebuild the structure
        if ($menu->children()->exists()) {
            app(MenuService::class)->clearCache();
        }
    }
}
