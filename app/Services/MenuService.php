<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Menu;

class MenuService extends BaseService
{
    protected int $cacheDuration = 86400; // 24 hours

    protected function getCacheKey(): string
    {
        return 'menu_structure';
    }

    public function getMenuStructure()
    {
        $cachedMenus = Cache::remember($this->getCacheKey(), $this->cacheDuration, function () {
            $allMenus = Menu::where('is_active', true)->get();

            return $this->buildMultiLevelMenu($allMenus)->groupBy('group');
        });

        // Filter cached menus based on current user permissions
        return $this->filterMenusByPermission($cachedMenus);
    }

    protected function buildMultiLevelMenu($menus, $parentId = null, $level = 0)
    {
        return $menus->filter(function ($menu) use ($parentId) {
            return $menu->parent_id == $parentId;
        })->map(function ($menu) use ($menus, $level) {
            $menu->children = $this->buildMultiLevelMenu($menus, $menu->id, $level + 1);
            $menu->level = $level;
            return $menu;
        });
    }

    protected function filterMenusByPermission($menuGroups)
    {
        return $menuGroups->map(function ($menus) {
            return $menus->filter(function ($menu) {
                return !$menu->permission_name || auth()->user()->can($menu->permission_name);
            })->map(function ($menu) {
                if ($menu->children && $menu->children->isNotEmpty()) {
                    $menu->children = $this->filterMenusByPermission(collect([$menu->children]));
                    // Flatten the collection since we wrapped it in an array
                    $menu->children = $menu->children->first();
                }
                return $menu;
            });
        });
    }

    // public function clearCache()
    // {
    //     Cache::forget($this->getCacheKey());
    // }
}
