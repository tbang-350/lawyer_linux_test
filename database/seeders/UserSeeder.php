<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create lawyer user
        User::create([
            'name' => 'John Smith',
            'email' => 'lawyer@example.com',
            'password' => Hash::make('password'),
            'role' => 'lawyer',
        ]);
    }
}
