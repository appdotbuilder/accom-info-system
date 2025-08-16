<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\AccommodationOwner;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $types = ['apartment', 'house', 'condo', 'villa', 'studio', 'loft', 'townhouse'];
        $amenities = [
            'WiFi', 'Air Conditioning', 'Heating', 'Kitchen', 'Washer', 'Dryer',
            'TV', 'Parking', 'Pool', 'Gym', 'Balcony', 'Garden', 'Hot Tub',
            'Fireplace', 'Dishwasher', 'Microwave', 'Coffee Maker'
        ];

        $bedrooms = fake()->numberBetween(1, 5);
        $bathrooms = fake()->numberBetween(1, min($bedrooms + 1, 4));
        $maxGuests = fake()->numberBetween($bedrooms, $bedrooms * 2 + 2);

        return [
            'accommodation_owner_id' => AccommodationOwner::factory(),
            'name' => fake()->sentence(3, false),
            'description' => fake()->paragraphs(3, true),
            'type' => fake()->randomElement($types),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->randomElement(['CA', 'NY', 'FL', 'TX', 'WA', 'NV', 'OR', 'CO', 'AZ', 'NC']),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'max_guests' => $maxGuests,
            'bedrooms' => $bedrooms,
            'bathrooms' => $bathrooms,
            'price_per_night' => fake()->randomFloat(2, 50, 500),
            'amenities' => fake()->randomElements($amenities, fake()->numberBetween(3, 8)),
            'photos' => [
                fake()->imageUrl(800, 600, 'house'),
                fake()->imageUrl(800, 600, 'house'),
                fake()->imageUrl(800, 600, 'house'),
            ],
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the property is inactive.
     */
    public function inactive(): static
    {
        return $this->state(function (array $attributes) {
            return ['is_active' => false];
        });
    }

    /**
     * Create a luxury property.
     */
    public function luxury(): static
    {
        $types = ['villa', 'penthouse', 'mansion'];
        $type = fake()->randomElement($types);
        $bedrooms = fake()->numberBetween(3, 8);
        $bathrooms = fake()->numberBetween(2, 6);
        $maxGuests = fake()->numberBetween(6, 16);
        $price = fake()->randomFloat(2, 200, 1000);
        
        $amenities = [
            'WiFi', 'Air Conditioning', 'Heating', 'Kitchen', 'Washer', 'Dryer',
            'TV', 'Parking', 'Pool', 'Gym', 'Balcony', 'Garden', 'Hot Tub',
            'Fireplace', 'Dishwasher', 'Microwave', 'Coffee Maker', 'Concierge',
            'Spa', 'Tennis Court', 'Wine Cellar', 'Home Theater'
        ];
        
        return $this->state(function (array $attributes) use ($type, $price, $bedrooms, $bathrooms, $maxGuests, $amenities) {
            return [
                'type' => $type,
                'price_per_night' => $price,
                'bedrooms' => $bedrooms,
                'bathrooms' => $bathrooms,
                'max_guests' => $maxGuests,
                'amenities' => $amenities,
            ];
        });
    }
}