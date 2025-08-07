<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin CarryWay',
            'email' => 'admin@carryway.ma',
            'phone' => '0612345678',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'approved',
            'approved_at' => now(),
            'email_verified_at' => now(),
        ]);

        // Create admin user details
        UserDetail::create([
            'user_id' => $admin->id,
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'city' => 'Casablanca',
            'address' => 'Casablanca, Maroc',
            'cin' => 'AB123456',
            'verified' => true,
            'id_verification_status' => 'verified',
            'id_verified_at' => now(),
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@carryway.ma');
        $this->command->info('Password: admin123');
    }
}
