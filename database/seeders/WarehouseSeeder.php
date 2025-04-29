<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Warehouse::firstOrCreate(
            ['name' => 'Default Warehouse'],
            [
                'address' => '123 Main St',
                'city' => 'Anytown',
                'country' => 'USA',
                'phone' => '555-1234',
                'email' => 'warehouse@example.com',
                'is_default' => true,
            ]
        );

        Warehouse::firstOrCreate(
            ['name' => 'Secondary Warehouse'],
            [
                'address' => '456 Side St',
                'city' => 'Otherville',
                'country' => 'USA',
                'phone' => '555-5678',
                'email' => 'warehouse2@example.com',
                'is_default' => false,
            ]
        );
    }
}
