<?php

namespace Database\Seeders;

use App\Models\AccommodationOwner;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Review;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@accommo.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create accommodation owners
        $owners = [];
        for ($i = 1; $i <= 5; $i++) {
            $user = User::factory()->create([
                'name' => "Property Owner $i",
                'email' => "owner$i@accommo.com",
                'role' => 'accommodation_owner',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $owner = AccommodationOwner::factory()->create([
                'user_id' => $user->id,
                'business_name' => "Premier Properties Group $i",
            ]);

            $owners[] = $owner;
        }

        // Create properties for each owner
        foreach ($owners as $owner) {
            $properties = Property::factory()->count(random_int(2, 5))->create([
                'accommodation_owner_id' => $owner->id,
            ]);

            // Create some luxury properties
            Property::factory()->luxury()->count(1)->create([
                'accommodation_owner_id' => $owner->id,
            ]);
        }

        // Create customers
        $customers = [];
        for ($i = 1; $i <= 10; $i++) {
            $customer = User::factory()->create([
                'name' => "Customer $i",
                'email' => "customer$i@accommo.com",
                'role' => 'customer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $customers[] = $customer;
        }

        // Create staff users
        $cleaningStaff = [];
        for ($i = 1; $i <= 3; $i++) {
            $staff = User::factory()->create([
                'name' => "Cleaning Staff $i",
                'email' => "cleaner$i@accommo.com",
                'role' => 'cleaning_staff',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $cleaningStaff[] = $staff;
        }

        $securityStaff = [];
        for ($i = 1; $i <= 2; $i++) {
            $staff = User::factory()->create([
                'name' => "Security Staff $i",
                'email' => "security$i@accommo.com",
                'role' => 'security_staff',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $securityStaff[] = $staff;
        }

        // Create bookings
        $properties = Property::all();
        foreach ($customers as $customer) {
            // Create some past bookings
            $pastBookings = Booking::factory()
                ->count(random_int(1, 3))
                ->past()
                ->create([
                    'user_id' => $customer->id,
                    'property_id' => $properties->random()->id,
                ]);

            // Create reviews for past bookings
            foreach ($pastBookings as $booking) {
                if (random_int(1, 100) <= 70) { // 70% chance of review
                    Review::factory()->create([
                        'booking_id' => $booking->id,
                        'property_id' => $booking->property_id,
                        'user_id' => $customer->id,
                        'rating' => random_int(3, 5),
                        'comment' => fake()->paragraph(),
                        'is_approved' => true,
                    ]);
                }
            }

            // Create future bookings
            if (random_int(1, 100) <= 60) { // 60% chance of future booking
                Booking::factory()
                    ->future()
                    ->create([
                        'user_id' => $customer->id,
                        'property_id' => $properties->random()->id,
                    ]);
            }
        }

        // Create service requests for some bookings
        $bookings = Booking::where('status', '!=', 'cancelled')->get();
        foreach ($bookings->random(min(20, $bookings->count())) as $booking) {
            ServiceRequest::factory()->create([
                'booking_id' => $booking->id,
                'type' => fake()->randomElement(['cleaning', 'maintenance', 'amenity', 'other']),
                'title' => fake()->sentence(4),
                'description' => fake()->paragraph(),
                'priority' => fake()->randomElement(['low', 'medium', 'high']),
                'status' => fake()->randomElement(['open', 'in_progress', 'completed']),
                'assigned_to' => fake()->randomElement([null, ...$cleaningStaff])?->id,
            ]);
        }

        $this->command->info('Accommodation system seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@accommo.com / password');
        $this->command->info('Owner: owner1@accommo.com / password');
        $this->command->info('Customer: customer1@accommo.com / password');
        $this->command->info('Cleaning: cleaner1@accommo.com / password');
        $this->command->info('Security: security1@accommo.com / password');
    }
}