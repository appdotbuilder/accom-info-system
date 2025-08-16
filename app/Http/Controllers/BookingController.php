<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Property;
use Carbon\Carbon;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index()
    {
        $user = auth()->user();
        
        $query = Booking::with(['property', 'user']);

        if ($user->isCustomer()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isAccommodationOwner()) {
            $query->whereHas('property', function ($q) use ($user) {
                $q->where('accommodation_owner_id', $user->accommodationOwner->id);
            });
        } elseif (!$user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $bookings = $query->latest()->paginate(10);

        return Inertia::render('bookings/index', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Property $property)
    {
        $property->load('accommodationOwner.user');

        return Inertia::render('bookings/create', [
            'property' => $property
        ]);
    }

    /**
     * Store a newly created booking.
     */
    public function store(StoreBookingRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        // Calculate total price
        $property = Property::findOrFail($validated['property_id']);
        $checkIn = Carbon::parse($validated['check_in_date']);
        $checkOut = Carbon::parse($validated['check_out_date']);
        $nights = $checkIn->diffInDays($checkOut);
        $validated['total_price'] = $nights * $property->price_per_night;

        $booking = Booking::create($validated);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        $user = auth()->user();

        // Check authorization
        if ($user->isCustomer() && $booking->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        } elseif ($user->isAccommodationOwner() && $booking->property->accommodation_owner_id !== $user->accommodationOwner->id) {
            abort(403, 'Unauthorized.');
        } elseif (!$user->isAdmin() && !$user->isCustomer() && !$user->isAccommodationOwner()) {
            abort(403, 'Unauthorized.');
        }

        $booking->load([
            'property.accommodationOwner.user',
            'user',
            'serviceRequests.assignedTo',
            'review'
        ]);

        return Inertia::render('bookings/show', [
            'booking' => $booking
        ]);
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && ($user->isCustomer() && $booking->user_id !== $user->id)) {
            abort(403, 'Unauthorized.');
        }

        $booking->load('property');

        return Inertia::render('bookings/edit', [
            'booking' => $booking
        ]);
    }

    /**
     * Update the specified booking.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $user = $request->user();

        if (!$user->isAdmin() && ($user->isCustomer() && $booking->user_id !== $user->id)) {
            abort(403, 'Unauthorized.');
        }

        // Only allow status updates for accommodation owners and admins
        $validated = $request->validated();
        if ($user->isCustomer() && isset($validated['status'])) {
            unset($validated['status']);
        }

        // Recalculate total price if dates changed
        if (isset($validated['check_in_date']) || isset($validated['check_out_date'])) {
            $checkIn = Carbon::parse($validated['check_in_date'] ?? $booking->check_in_date);
            $checkOut = Carbon::parse($validated['check_out_date'] ?? $booking->check_out_date);
            $nights = $checkIn->diffInDays($checkOut);
            $validated['total_price'] = $nights * $booking->property->price_per_night;
        }

        $booking->update($validated);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified booking.
     */
    public function destroy(Booking $booking)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && ($user->isCustomer() && $booking->user_id !== $user->id)) {
            abort(403, 'Unauthorized.');
        }

        // Only allow cancellation, not deletion
        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
}