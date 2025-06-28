<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Using firstOrCreate prevents duplication if the seeder is run multiple times.
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $counselorRole = Role::firstOrCreate(['name' => 'Counselor', 'guard_name' => 'web']);

        // Create the Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        // Create the Counselor User
        $counselor = User::firstOrCreate(
            ['email' => 'counselor@example.com'],
            [
                'name' => 'Counselor User',
                'password' => Hash::make('password'),
            ]
        );
        $counselor->assignRole($counselorRole);
    }
}
