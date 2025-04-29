<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the default warehouse
        $defaultWarehouse = Warehouse::where('is_default', true)->first();

        if (!$defaultWarehouse) {
            $this->command->error('Default warehouse not found. Please run WarehouseSeeder first.');
            return;
        }

        // Create products using the factory
        Product::factory(20)->create()->each(function ($product) use ($defaultWarehouse) {
            // Add stock to the default warehouse for each created product
            DB::table('product_warehouse')->insert([
                'product_id' => $product->id,
                'warehouse_id' => $defaultWarehouse->id,
                'quantity' => rand(10, 100), // Random initial stock
                'alert_quantity' => rand(5, 15), // Random alert level
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        // You can also create specific products manually if needed
        // Product::create([...]);
        // DB::table('product_warehouse')->insert([...]);
    }
}
