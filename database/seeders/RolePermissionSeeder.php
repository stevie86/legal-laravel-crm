<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage clients']);
        Permission::firstOrCreate(['name' => 'manage sessions']);
        Permission::firstOrCreate(['name' => 'view reports']);

        // create roles and assign created permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $counselorRole = Role::firstOrCreate(['name' => 'Counselor']);
        $counselorRole->givePermissionTo(['manage clients', 'manage sessions', 'view reports']);

        $viewerRole = Role::firstOrCreate(['name' => 'Viewer']);
        $viewerRole->givePermissionTo('view reports');
    }
}