<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Route::get('/', function () {
//     return view('welcome')->name('home');
// });

Route::view('/', 'welcome')->name('home');

Volt::route('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(
    function () {
        // Route::redirect('dashboard', 'dashboard');
        // Route::get('dashboard', function () {
        //     return view('dashboard');
        // })->name('dashboard');
        Route::get('menu-manager', \App\Livewire\MenuManager::class)->name('menu-manager');
        Route::get('menu-manager/{id}', \App\Livewire\MenuManager::class)->name('menu-manager.id');
        Route::get('menu-manager/{id}/{parentId}', \App\Livewire\MenuManager::class)->name('menu-manager.id.parentId');
        Route::get('menu-manager/{id}/{parentId}/{order}', \App\Livewire\MenuManager::class)->name('menu-manager.id.parentId.order');
        Route::get('menu-manager/{id}/{parentId}/{order}/{name}', \App\Livewire\MenuManager::class)->name('menu-manager.id.parentId.order.name');

        Route::get('menus', \App\Livewire\Menus\MenuList::class)->name('menus');
        Route::get('menus/create', \App\Livewire\Menus\MenuList::class)->name('menus.create');
        Route::get('menus/{id}/edit', \App\Livewire\Menus\MenuList::class)->name('menus.edit');
    }
);

require __DIR__ . '/auth.php';
