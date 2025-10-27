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

        // Permissions untuk setiap modul
        $permissions = [
            // Property Management
            'properties.view',
            'properties.create',
            'properties.update',
            'properties.delete',
            'properties.publish',

            // Room Management
            'rooms.view',
            'rooms.create',
            'rooms.update',
            'rooms.delete',
            'rooms.change_status',

            // Tenant Management
            'tenants.view',
            'tenants.create',
            'tenants.update',
            'tenants.delete',
            'tenants.verify',

            // Booking Management
            'bookings.view',
            'bookings.create',
            'bookings.confirm',
            'bookings.cancel',

            // Contract Management
            'contracts.view',
            'contracts.create',
            'contracts.update',
            'contracts.terminate',
            'contracts.extend',

            // Invoice Management
            'invoices.view',
            'invoices.create',
            'invoices.update',
            'invoices.delete',
            'invoices.send_reminder',

            // Payment Management
            'payments.view',
            'payments.create',
            'payments.verify',
            'payments.reject',

            // Maintenance Ticket Management
            'tickets.view',
            'tickets.create',
            'tickets.assign',
            'tickets.update',
            'tickets.close',

            // Announcement Management
            'announcements.view',
            'announcements.create',
            'announcements.update',
            'announcements.delete',

            // Review Management
            'reviews.view',
            'reviews.create',
            'reviews.reply',
            'reviews.moderate',

            // Voucher Management
            'vouchers.view',
            'vouchers.create',
            'vouchers.update',
            'vouchers.delete',

            // Report & Analytics
            'reports.view',
            'reports.export',
            'analytics.view',

            // Settings
            'settings.view',
            'settings.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Create Roles
        $owner = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $tenant = Role::firstOrCreate(['name' => 'tenant', 'guard_name' => 'web']);

        // Owner - Full Access
        $owner->givePermissionTo(Permission::all());

        // Admin - Manage Operations
        $admin->givePermissionTo([
            // Properties
            'properties.view',
            'properties.create',
            'properties.update',
            'properties.delete',
            'properties.publish',

            // Rooms
            'rooms.view',
            'rooms.create',
            'rooms.update',
            'rooms.delete',
            'rooms.change_status',

            // Tenants
            'tenants.view',
            'tenants.create',
            'tenants.update',
            'tenants.delete',
            'tenants.verify',

            // Bookings
            'bookings.view',
            'bookings.create',
            'bookings.confirm',
            'bookings.cancel',

            // Contracts
            'contracts.view',
            'contracts.create',
            'contracts.update',
            'contracts.terminate',
            'contracts.extend',

            // Invoices
            'invoices.view',
            'invoices.create',
            'invoices.update',
            'invoices.send_reminder',

            // Payments
            'payments.view',
            'payments.create',
            'payments.verify',
            'payments.reject',

            // Maintenance
            'tickets.view',
            'tickets.assign',
            'tickets.update',
            'tickets.close',

            // Announcements
            'announcements.view',
            'announcements.create',
            'announcements.update',
            'announcements.delete',

            // Reviews
            'reviews.view',
            'reviews.reply',
            'reviews.moderate',

            // Reports
            'reports.view',
            'reports.export',
        ]);

        // Staff - Limited Operations
        $staff->givePermissionTo([
            // View only
            'properties.view',
            'rooms.view',
            'tenants.view',
            'bookings.view',
            'contracts.view',

            // Invoices & Payments
            'invoices.view',
            'invoices.update',
            'payments.view',
            'payments.create',

            // Maintenance tickets - can handle
            'tickets.view',
            'tickets.update',
            'tickets.close',

            // Announcements - view only
            'announcements.view',
        ]);

        // Tenant - View Own Data Only
        $tenant->givePermissionTo([
            'contracts.view',
            'invoices.view',
            'payments.create',
            'tickets.view',
            'tickets.create',
            'announcements.view',
            'reviews.create',
        ]);
    }
}
