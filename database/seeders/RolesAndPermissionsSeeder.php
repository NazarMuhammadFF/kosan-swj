<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions inti
        $perms = [
            'rooms.view', 'rooms.create', 'rooms.update', 'rooms.delete',
            'tenants.view','tenants.create','tenants.update','tenants.delete',
            'contracts.view','contracts.create','contracts.update','contracts.end',
            'invoices.view','invoices.create','invoices.update','invoices.pay',
        ];
        foreach ($perms as $p) Permission::firstOrCreate(['name'=>$p, 'guard_name'=>'web']);

        // Roles
        $owner  = Role::firstOrCreate(['name'=>'owner','guard_name'=>'web']);
        $admin  = Role::firstOrCreate(['name'=>'admin','guard_name'=>'web']);
        $staff  = Role::firstOrCreate(['name'=>'staff','guard_name'=>'web']);
        $tenant = Role::firstOrCreate(['name'=>'tenant','guard_name'=>'web']);

        // Mapping permission ke role
        $owner->givePermissionTo(Permission::all()); // bos bebas

        $admin->givePermissionTo([
            'rooms.view','rooms.create','rooms.update','rooms.delete',
            'tenants.view','tenants.create','tenants.update','tenants.delete',
            'contracts.view','contracts.create','contracts.update','contracts.end',
            'invoices.view','invoices.create','invoices.update',
        ]);

        $staff->givePermissionTo([
            'rooms.view',
            'tenants.view',
            'contracts.view',
            'invoices.view','invoices.update','invoices.pay',
        ]);

        // tenant cuma lihat
        $tenant->givePermissionTo([
            'contracts.view','invoices.view',
        ]);
    }
}
