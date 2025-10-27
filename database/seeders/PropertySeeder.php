<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $propertiesToCreate = 15;

        // Ensure we have enough owners to attach properties to
        $owners = User::role('owner')->get();

        if ($owners->count() < 3) {
            $additionalOwners = User::factory()
                ->count(3 - $owners->count())
                ->create();

            $additionalOwners->each(fn (User $user) => $user->assignRole('owner'));

            $owners = $owners->concat($additionalOwners);
        }

        Property::factory()
            ->count($propertiesToCreate)
            ->state(fn () => [
                'owner_id' => $owners->random()->id,
            ])
            ->create();

        $this->command?->info("Generated {$propertiesToCreate} dummy properties.");
    }
}
