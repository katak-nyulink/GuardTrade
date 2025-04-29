<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default supplier
        Supplier::firstOrCreate(
            ['name' => 'Default Supplier'],
            [
                'company_name' => 'Default Supply Co.',
                'email' => 'supplier@example.com',
                'phone' => '555-0000',
            ]
        );

        // Create some random suppliers using the factory
        Supplier::factory(5)->create();
    }
}
