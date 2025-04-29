<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $piece = Unit::firstOrCreate(
            ['short_name' => 'pc'],
            ['name' => 'Piece']
        );

        Unit::firstOrCreate(
            ['short_name' => 'kg'],
            ['name' => 'Kilogram']
        );

        Unit::firstOrCreate(
            ['short_name' => 'ltr'],
            ['name' => 'Liter']
        );

        // Example of a derived unit (1 Box = 12 Pieces)
        Unit::firstOrCreate(
            ['short_name' => 'box'],
            [
                'name' => 'Box',
                'base_unit_id' => $piece->id,
                'operator' => '*',
                'operator_value' => 12,
            ]
        );
    }
}
