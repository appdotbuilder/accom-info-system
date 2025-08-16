<?php

namespace Database\Factories;

use App\Models\AccommodationOwner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccommodationOwner>
 */
class AccommodationOwnerFactory extends Factory
{
    protected $model = AccommodationOwner::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'business_name' => fake()->company() . ' Properties',
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'description' => fake()->paragraph(3),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the accommodation owner is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}