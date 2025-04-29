<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Change this in production!
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'is_admin' => true,
                'is_active' => true
                // Add team creation if using Jetstream teams extensively
            ]
        );

        // Optionally create more users using the factory
        User::factory(50)->create();
    }
}
