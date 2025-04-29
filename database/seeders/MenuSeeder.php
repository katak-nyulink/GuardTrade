<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ===== Sidebar Menu Seeder for Inventory App =====

        // Dashboard
        Menu::create([
            'title' => 'Dashboard',
            'icon' => 'home',
            'route' => 'dashboard',
            'type' => 'link',
            'group' => null,
            'permission_name' => 'view dashboard',
        ]);

        // Inventory Section
        $inventoryDropdown = Menu::create([
            'title' => 'Inventory',
            'icon' => 'archive-box',
            'type' => 'dropdown',
            'group' => 'Modules',
            'permission_name' => null,
        ]);

        Menu::create([
            'title' => 'Products',
            'route' => 'products.index',
            'parent_id' => $inventoryDropdown->id,
            'type' => 'link',
            'permission_name' => 'view products',
        ]);

        Menu::create([
            'title' => 'Categories',
            'route' => 'categories.index',
            'parent_id' => $inventoryDropdown->id,
            'type' => 'link',
            'permission_name' => 'view categories',
        ]);

        Menu::create([
            'title' => 'Stock Adjustments',
            'route' => 'stock.adjustments',
            'parent_id' => $inventoryDropdown->id,
            'type' => 'link',
            'permission_name' => 'manage stock',
        ]);

        // Warehouses Section
        $warehouseDropdown = Menu::create([
            'title' => 'Warehouses',
            'icon' => 'building-storefront',
            'type' => 'dropdown',
            'group' => 'Modules',
            'permission_name' => null,
        ]);

        Menu::create([
            'title' => 'Warehouse List',
            'route' => 'warehouses.index',
            'parent_id' => $warehouseDropdown->id,
            'type' => 'link',
            'permission_name' => 'view warehouses',
        ]);

        Menu::create([
            'title' => 'Transfers',
            'route' => 'warehouses.transfers',
            'parent_id' => $warehouseDropdown->id,
            'type' => 'link',
            'permission_name' => 'transfer stock',
        ]);

        // Transactions
        $transactionDropdown = Menu::create([
            'title' => 'Transactions',
            'icon' => 'exchange',
            'type' => 'dropdown',
            'group' => 'Modules',
            'permission_name' => null,
        ]);

        Menu::create([
            'title' => 'Sales',
            'route' => 'sales.index',
            'parent_id' => $transactionDropdown->id,
            'type' => 'link',
            'permission_name' => 'view sales',
        ]);

        Menu::create([
            'title' => 'Purchases',
            'route' => 'purchases.index',
            'parent_id' => $transactionDropdown->id,
            'type' => 'link',
            'permission_name' => 'view purchases',
        ]);

        Menu::create([
            'title' => 'Returns',
            'route' => 'returns.index',
            'parent_id' => $transactionDropdown->id,
            'type' => 'link',
            'permission_name' => 'view returns',
        ]);

        // Accounting
        $accountingDropdown = Menu::create([
            'title' => 'Accounting',
            'icon' => 'document',
            'type' => 'dropdown',
            'group' => 'Modules',
            'permission_name' => null,
        ]);

        Menu::create([
            'title' => 'Chart of Accounts',
            'route' => 'accounts.index',
            'parent_id' => $accountingDropdown->id,
            'type' => 'link',
            'permission_name' => 'view accounts',
        ]);

        Menu::create([
            'title' => 'Journals',
            'route' => 'journals.index',
            'parent_id' => $accountingDropdown->id,
            'type' => 'link',
            'permission_name' => 'view journals',
        ]);

        // Master Data
        $masterDropdown = Menu::create([
            'title' => 'Master Data',
            'icon' => 'circle-stack',
            'type' => 'dropdown',
            'group' => 'Settings',
            'permission_name' => null,
        ]);

        Menu::create([
            'title' => 'Suppliers',
            'route' => 'suppliers.index',
            'parent_id' => $masterDropdown->id,
            'type' => 'link',
            'permission_name' => 'view suppliers',
        ]);

        Menu::create([
            'title' => 'Customers',
            'route' => 'customers.index',
            'parent_id' => $masterDropdown->id,
            'type' => 'link',
            'permission_name' => 'view customers',
        ]);
    }
}
