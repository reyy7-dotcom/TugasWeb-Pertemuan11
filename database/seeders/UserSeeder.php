<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin account
        User::firstOrCreate(
            ['email' => 'admin@ecommerce.test'],
            [
                'name' => 'Administrator E-Commerce',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'phone' => '081234567890',
                'email_verified_at' => now(),
            ]
        );

        // 2. Editor account
        User::firstOrCreate(
            ['email' => 'editor@ecommerce.test'],
            [
                'name' => 'Editor Content & Catalog',
                'password' => Hash::make('password'),
                'role' => User::ROLE_EDITOR,
                'phone' => '081298765432',
                'email_verified_at' => now(),
            ]
        );

        // 3. Standard Customer / User account
        User::firstOrCreate(
            ['email' => 'user@ecommerce.test'],
            [
                'name' => 'Customer Setia',
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
                'phone' => '082155551234',
                'email_verified_at' => now(),
            ]
        );

        // 4. Create 10 additional realistic customer users
        User::factory()->count(10)->create([
            'role' => User::ROLE_USER,
        ]);
    }
}
