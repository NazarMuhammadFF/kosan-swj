<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Owner Account
        $owner = User::create([
            'name' => 'Owner Admin',
            'email' => 'owner@kosan.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $owner->assignRole('owner');

        // Admin Account
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@kosan.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Staff Account
        $staff = User::create([
            'name' => 'Staff User',
            'email' => 'staff@kosan.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $staff->assignRole('staff');

        // Tenant Account
        $tenant = User::create([
            'name' => 'Tenant Demo',
            'email' => 'tenant@kosan.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $tenant->assignRole('tenant');

        $this->command->info('Created test users:');
        $this->command->info('- Owner: owner@kosan.com / password');
        $this->command->info('- Admin: admin@kosan.com / password');
        $this->command->info('- Staff: staff@kosan.com / password');
        $this->command->info('- Tenant: tenant@kosan.com / password');
    }
}
