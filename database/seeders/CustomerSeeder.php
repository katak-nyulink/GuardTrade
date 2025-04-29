<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default walk-in customer
        Customer::firstOrCreate(
            ['name' => 'Walk-in Customer'],
            [
                'email' => 'walkin@example.com', // Use a placeholder email
                'phone' => 'N/A',
            ]
        );

        // Create some random customers using the factory
        Customer::factory(10)->create();
    }
}
