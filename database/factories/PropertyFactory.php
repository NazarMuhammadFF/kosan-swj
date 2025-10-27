<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company() . ' ' . $this->faker->randomElement(['Kost', 'Kos', 'Boarding House']);

        return [
            'owner_id' => User::role('owner')->inRandomOrder()->first()?->id ?? User::factory()->create(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(3, true),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'province' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'latitude' => $this->faker->latitude(-10, 5),
            'longitude' => $this->faker->longitude(95, 141),
            'phone' => $this->faker->phoneNumber(),
            'gender_type' => $this->faker->randomElement(['male', 'female', 'mixed']),
            'facilities' => [
                $this->faker->randomElement(['WiFi', 'Parkir Motor', 'Parkir Mobil']),
                $this->faker->randomElement(['Dapur Umum', 'R. Cuci', 'R. Jemur']),
                $this->faker->randomElement(['CCTV', 'Security 24 Jam', 'Keamanan']),
            ],
            'rules' => [
                'Dilarang membawa tamu menginap',
                'Dilarang merokok di dalam kamar',
                'Jam malam pukul 22.00',
                'Wajib menjaga kebersihan',
            ],
            'deposit_amount' => $this->faker->randomElement([500000, 1000000, 1500000, 2000000]),
            'photos' => [
                'https://via.placeholder.com/800x600.png?text=Property+Photo+1',
                'https://via.placeholder.com/800x600.png?text=Property+Photo+2',
                'https://via.placeholder.com/800x600.png?text=Property+Photo+3',
            ],
            'video_url' => $this->faker->optional(0.3)->url(),
            'is_published' => $this->faker->boolean(80),
            'is_featured' => $this->faker->boolean(20),
        ];
    }

    /**
     * Indicate that the property is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    /**
     * Indicate that the property is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the property is for males only.
     */
    public function maleOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender_type' => 'male',
        ]);
    }

    /**
     * Indicate that the property is for females only.
     */
    public function femaleOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender_type' => 'female',
        ]);
    }

    /**
     * Indicate that the property is mixed gender.
     */
    public function mixed(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender_type' => 'mixed',
        ]);
    }
}
