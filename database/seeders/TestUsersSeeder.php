<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Firm;
use App\Models\LawyerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test firm
        $firm = Firm::create([
            'name' => 'Test Law Firm',
            'address' => '123 Legal Street',
            'phone' => '555-0123',
            'email' => 'contact@testlawfirm.com',
            'website' => 'https://testlawfirm.com',
            'description' => 'A leading law firm specializing in various legal services',
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@testlawfirm.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'firm_id' => $firm->id,
        ]);

        // Create lawyer user
        $lawyer = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@testlawfirm.com',
            'password' => Hash::make('password'),
            'role' => 'lawyer',
            'firm_id' => $firm->id,
        ]);

        // Create lawyer profile
        LawyerProfile::create([
            'user_id' => $lawyer->id,
            'bar_number' => 'BAR123456',
            'specialization' => 'Corporate Law',
            'bio' => 'Experienced corporate lawyer with over 10 years of practice.',
            'phone' => '555-0124',
            'office_location' => 'Suite 101',
        ]);
    }
}
