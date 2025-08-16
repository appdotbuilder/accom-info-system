<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $booking = Booking::factory()->create();

        return [
            'booking_id' => $booking->id,
            'property_id' => $booking->property_id,
            'user_id' => $booking->user_id,
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->optional(0.8)->paragraph(),
            'is_approved' => fake()->boolean(90), // 90% approved
        ];
    }

    /**
     * Indicate that the review is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }

    /**
     * Indicate that the review is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }

    /**
     * Create a positive review.
     */
    public function positive(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->numberBetween(4, 5),
            'comment' => fake()->randomElement([
                'Amazing stay! The property was exactly as described and the host was very responsive.',
                'Beautiful location and clean accommodations. Would definitely book again!',
                'Exceeded our expectations. Great amenities and perfect for our family vacation.',
                'Wonderful property with all the comforts of home. Highly recommended!',
                'The host was super helpful and the place was spotless. Great experience overall.'
            ]),
            'is_approved' => true,
        ]);
    }

    /**
     * Create a negative review.
     */
    public function negative(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->numberBetween(1, 2),
            'comment' => fake()->randomElement([
                'The property was not as clean as expected and some amenities were not working.',
                'Disappointing stay. The photos were misleading and the location was not ideal.',
                'Had several issues during our stay that were not resolved promptly.',
                'The property needs maintenance and the communication was poor.',
                'Not worth the price. Several problems that made the stay uncomfortable.'
            ]),
            'is_approved' => true,
        ]);
    }
}