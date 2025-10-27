<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in order
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            PropertySeeder::class,
            // RoomSeeder::class, // TODO: update RoomSeeder to use factories before enabling
        ]);
    }
}
