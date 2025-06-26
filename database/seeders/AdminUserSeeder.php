<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin-Benutzer erstellen
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@beratung-crm.de',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Berater-Benutzer erstellen
        \App\Models\User::create([
            'name' => 'Dr. Maria Schmidt',
            'email' => 'berater@beratung-crm.de',
            'password' => \Illuminate\Support\Facades\Hash::make('berater123'),
            'role' => 'counselor',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Betrachter-Benutzer erstellen
        \App\Models\User::create([
            'name' => 'Praktikant Max',
            'email' => 'viewer@beratung-crm.de',
            'password' => \Illuminate\Support\Facades\Hash::make('viewer123'),
            'role' => 'viewer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Benutzer erstellt:');
        $this->command->info('Admin: admin@beratung-crm.de / admin123');
        $this->command->info('Berater: berater@beratung-crm.de / berater123');
        $this->command->info('Betrachter: viewer@beratung-crm.de / viewer123');
    }
}
