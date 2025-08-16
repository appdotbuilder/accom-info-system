<?php

namespace Database\Factories;

use App\Models\ServiceRequest;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceRequest>
 */
class ServiceRequestFactory extends Factory
{
    protected $model = ServiceRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            'cleaning' => [
                'Extra towels needed',
                'Room cleaning request',
                'Bathroom maintenance',
                'Kitchen cleaning needed',
                'Laundry service request'
            ],
            'maintenance' => [
                'Air conditioning not working',
                'WiFi connection issues',
                'Leaky faucet repair',
                'Light bulb replacement',
                'Door lock problems'
            ],
            'security' => [
                'Key card not working',
                'Suspicious activity report',
                'Access control issue',
                'Emergency contact needed',
                'Safety concern report'
            ],
            'amenity' => [
                'Pool access request',
                'Gym equipment issue',
                'Parking space needed',
                'Extra bedding request',
                'Kitchen utensils needed'
            ],
            'other' => [
                'General inquiry',
                'Check-in assistance needed',
                'Local area information',
                'Transportation help',
                'Restaurant recommendation'
            ]
        ];

        $type = fake()->randomElement(array_keys($types));
        $title = fake()->randomElement($types[$type]);

        return [
            'booking_id' => Booking::factory(),
            'type' => $type,
            'title' => $title,
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => fake()->randomElement(['open', 'in_progress', 'completed', 'cancelled']),
            'assigned_to' => fake()->optional(0.6)->randomElement(
                User::where('role', 'IN', ['cleaning_staff', 'security_staff'])->pluck('id')->toArray()
            ),
            'resolution_notes' => fake()->optional(0.4)->paragraph(),
        ];
    }

    /**
     * Create an urgent service request.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
            'status' => 'open',
            'type' => fake()->randomElement(['maintenance', 'security']),
            'title' => fake()->randomElement([
                'Emergency repair needed',
                'Urgent security issue',
                'Critical system failure',
                'Safety hazard reported'
            ]),
        ]);
    }

    /**
     * Create a completed service request.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'resolution_notes' => fake()->paragraph(),
        ]);
    }

    /**
     * Create a cleaning service request.
     */
    public function cleaning(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'cleaning',
            'title' => fake()->randomElement([
                'Extra towels needed',
                'Room cleaning request',
                'Bathroom maintenance',
                'Kitchen cleaning needed',
                'Laundry service request'
            ]),
        ]);
    }
}