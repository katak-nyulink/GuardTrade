<?php

namespace App\View\Components\Sidebar;

use App\Models\Menu;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    // public $menuItems;

    // public $groupMenu;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Define the menu items
    }

    /**
     * Check if the item is active based on the current URL.
     *
     * @param array $item
     * @return bool
     */

    // public function isActive($item)
    // {
    //     if (isset($item['url'])) {
    //         return request()->url() === $item['url'];
    //     }

    //     if (isset($item['subitems'])) {
    //         foreach ($item['subitems'] as $subitem) {
    //             if ($this->isActive($subitem)) {
    //                 return true;
    //             }
    //         }
    //     }

    //     return false;
    // }

    function getSidebarGroupedMenu()
    {
        $menus = Menu::with('children.children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return $menus->filter(function ($menu) {
            return !$menu->permission_name || auth()->user()->can($menu->permission_name);
        })->groupBy('group');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar.sidebar', [
            'groupedMenu' => $this->getSidebarGroupedMenu(),
            // 'groupMenu' => $this->groupMenu,
            // 'menuItems' => $this->menuItems,
        ]);
    }
}
