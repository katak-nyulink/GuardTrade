<?php

use App\Models\Menu;
use App\Models\User;

if (!function_exists('getMenu')) {
    function getMenu($menus = null)
    {
        if (is_null($menus)) {
            $menus = config('menu');
        }

        return collect($menus)->filter(function ($menu) {
            return auth()->user()->can($menu['permission']);
        })->toArray();
    }
}

if (!function_exists('getSidebarMenu')) {
    function getSidebarMenu()
    {
        $user = auth()->user();

        return Menu::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get()
            ->filter(function ($menu) use ($user) {
                return $menu->permission_name === null || $user->can($menu->permission_name);
            });
    }
}

if (!function_exists('getSidebarGroupedMenu')) {
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
}

function buildMenuNestable($menus)
{
    $html = '<ol class="dd-list">';
    foreach ($menus as $menu) {
        $html .= '<li class="dd-item" data-id="' . $menu->id . '">';
        $html .= '<div class="dd-handle flex items-center">' . $menu->title . ' <button wire:click="edit(' . $menu->id . ')" class="px-2 py-1 bg-primary text-white">Edit</button> <button wire:click="delete(' . $menu->id . ')" class="px-2 py-1 bg-red-500 text-white">Delete</button></div>';

        if ($menu->children && $menu->children->count()) {
            $html .= buildMenuNestable($menu->children);
        }

        $html .= '</li>';
    }
    $html .= '</ol>';
    return $html;
}

if (!function_exists('getUserRole')) {
    function getUserRole(?User $user)
    {
        return $user->roles->first()->name ?? 'guest';
    }
}
if (!function_exists('getUserName')) {
    function getUserName(?User $user)
    {
        return $user->name ?? 'Guest';
    }
}
