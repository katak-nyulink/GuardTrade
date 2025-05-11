<?php

// use App\Livewire\Categories\CategoryList;
// use App\Livewire\Users\UserForm;
// use App\Livewire\Users\UserList;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Route::get('/', function () {
//     return view('welcome')->name('home');
// });

Route::view('/', 'welcome')->name('home');

Volt::route('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::get('categories', \App\Livewire\Categories\CategoryList::class)->middleware(['auth', 'verified'])->name('categories');

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

        // Route::get('users', UserList::class)->name('users.index');
        // Route::get('users.create', UserForm::class)->name('models.create');
        // Route::get('users.edit', UserForm::class)->name('models.edit');


        Route::get('settings', \App\Livewire\Settings\SettingManager::class)->name('settings');
        Route::get('settings/{id}', \App\Livewire\Settings\SettingManager::class)->name('settings.id');
    }
);

require __DIR__ . '/auth.php';
