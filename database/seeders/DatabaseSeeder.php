<?php

namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(500)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserSeeder::class,
            WarehouseSeeder::class, // Needs to run before Products and Settings
            UnitSeeder::class,      // Needs to run before Products
            TaxRateSeeder::class,   // Needs to run before Products
            CategorySeeder::class,  // Needs to run before Products
            BrandSeeder::class,     // Needs to run before Products
            CustomerSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,   // Depends on Warehouse, Unit, TaxRate, Category, Brand
            AccountSeeder::class,   // Needs to run before Settings linking accounts
            SettingSeeder::class,   // Depends on Warehouse, Customer, TaxRate, Account
            // Add other seeders here (e.g., SaleSeeder, PurchaseSeeder) if you create them
        ]);
    }
}
