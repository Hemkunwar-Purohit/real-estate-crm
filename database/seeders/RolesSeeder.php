<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ FIX 1: Cache reset karo pehle
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ FIX 2: Permissions PEHLE banao
        $permissions = [
            'leads.view', 'leads.create', 'leads.edit', 'leads.delete',
            'properties.view', 'properties.create', 'properties.edit', 'properties.delete',
            'deals.view', 'deals.create', 'deals.edit', 'deals.delete',
            'clients.view', 'clients.create', 'clients.edit', 'clients.delete',
            'reports.view',
            'settings.manage',
            'users.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ✅ FIX 3: Roles banao BAAD MEIN
        $admin = Role::firstOrCreate(['name' => 'admin',   'guard_name' => 'web']);
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $agent = Role::firstOrCreate(['name' => 'agent',   'guard_name' => 'web']);

        // ✅ FIX 4: syncPermissions() Permission objects se karo, strings se nahi
        $admin->syncPermissions(Permission::all());

        $manager->syncPermissions(Permission::whereIn('name', [
            'leads.view', 'leads.create', 'leads.edit',
            'properties.view', 'properties.create', 'properties.edit',
            'deals.view', 'deals.create', 'deals.edit',
            'clients.view', 'clients.create', 'clients.edit',
            'reports.view',
        ])->get());

        $agent->syncPermissions(Permission::whereIn('name', [
            'leads.view', 'leads.create',
            'properties.view',
            'deals.view', 'deals.create',
            'clients.view', 'clients.create',
        ])->get());

        // Admin user banao
        $user = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $user->assignRole('admin');
    }
}
