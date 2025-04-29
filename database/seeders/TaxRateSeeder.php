<?php

namespace Database\Seeders;

use App\Models\TaxRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaxRate::firstOrCreate(
            ['name' => 'No Tax'],
            [
                'rate' => 0.00,
                'is_default' => true,
            ]
        );

        TaxRate::firstOrCreate(
            ['name' => 'Standard VAT (10%)'],
            [
                'rate' => 10.00,
                'is_default' => false,
            ]
        );
        TaxRate::firstOrCreate(
            ['name' => 'Standard VAT (11%)'],
            [
                'rate' => 11.00,
                'is_default' => false,
            ]
        );
        TaxRate::firstOrCreate(
            ['name' => 'Standard VAT (12%)'],
            [
                'rate' => 12.00,
                'is_default' => false,
            ]
        );
    }
}
