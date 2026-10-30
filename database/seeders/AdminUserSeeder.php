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
        if (!app()->environment(['local', 'testing'])) {
            throw new \LogicException('Demo admin seeding is restricted to local/testing environments.');
        }
        $password = config('seeding.admin_password');
        if (!is_string($password) || strlen($password) < 12) {
            throw new \RuntimeException('Set SEED_ADMIN_PASSWORD to a local secret of at least 12 characters.');
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Admin CarryWay',
            'email' => 'admin@carryway.ma',
            'phone' => '0612345678',
            'password' => Hash::make($password),
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
        $this->command->info('Use the locally configured admin credentials; passwords are not printed.');
    }
}
