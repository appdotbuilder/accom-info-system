<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $property = Property::factory()->create();
        $checkInDate = fake()->dateTimeBetween('now', '+3 months');
        $checkOutDate = fake()->dateTimeBetween($checkInDate, $checkInDate->format('Y-m-d') . ' +14 days');
        
        $nights = Carbon::parse($checkInDate)->diffInDays(Carbon::parse($checkOutDate));
        $totalPrice = $nights * $property->price_per_night;

        return [
            'property_id' => $property->id,
            'user_id' => User::factory(),
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'guests' => fake()->numberBetween(1, min($property->max_guests, 6)),
            'total_price' => $totalPrice,
            'status' => fake()->randomElement(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled']),
            'special_requests' => fake()->optional(0.3)->paragraph(),
        ];
    }

    /**
     * Indicate that the booking is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    /**
     * Indicate that the booking is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Create a future booking.
     */
    public function future(): static
    {
        $checkInDate = fake()->dateTimeBetween('+1 week', '+3 months');
        $checkOutDate = fake()->dateTimeBetween($checkInDate, $checkInDate->format('Y-m-d') . ' +14 days');

        return $this->state(fn (array $attributes) => [
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'status' => 'confirmed',
        ]);
    }

    /**
     * Create a past booking.
     */
    public function past(): static
    {
        $checkInDate = fake()->dateTimeBetween('-6 months', '-1 week');
        $checkOutDate = fake()->dateTimeBetween($checkInDate, $checkInDate->format('Y-m-d') . ' +14 days');

        return $this->state(fn (array $attributes) => [
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'status' => 'checked_out',
        ]);
    }
}