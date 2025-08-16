<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use App\Models\AccommodationOwner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties.
     */
    public function index(Request $request)
    {
        $query = Property::with(['accommodationOwner.user', 'reviews'])
            ->active();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->get('city'));
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        // Filter by max guests
        if ($request->filled('guests')) {
            $query->where('max_guests', '>=', $request->get('guests'));
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->get('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->get('max_price'));
        }

        $properties = $query->latest()->paginate(12);

        // Get unique cities and types for filters
        $cities = Property::active()->distinct()->pluck('city')->sort();
        $types = Property::active()->distinct()->pluck('type')->sort();

        return Inertia::render('properties/index', [
            'properties' => $properties,
            'cities' => $cities,
            'types' => $types,
            'filters' => $request->only(['search', 'city', 'type', 'guests', 'min_price', 'max_price'])
        ]);
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        return Inertia::render('properties/create');
    }

    /**
     * Store a newly created property.
     */
    public function store(StorePropertyRequest $request)
    {
        $user = $request->user();
        $accommodationOwner = $user->accommodationOwner;

        if (!$accommodationOwner) {
            return redirect()->back()->withErrors(['error' => 'You must be an accommodation owner to create properties.']);
        }

        $validated = $request->validated();
        $validated['accommodation_owner_id'] = $accommodationOwner->id;

        $property = Property::create($validated);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property created successfully.');
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        $property->load([
            'accommodationOwner.user',
            'reviews.user',
            'bookings' => function ($query) {
                $query->where('status', '!=', 'cancelled')
                      ->select('property_id', 'check_in_date', 'check_out_date');
            }
        ]);

        // Calculate average rating
        $averageRating = $property->reviews()->where('is_approved', true)->avg('rating');
        $totalReviews = $property->reviews()->where('is_approved', true)->count();

        // Get unavailable dates from bookings
        $unavailableDates = $property->bookings->map(function ($booking) {
            return [
                'start' => $booking->check_in_date->format('Y-m-d'),
                'end' => $booking->check_out_date->format('Y-m-d'),
            ];
        });

        return Inertia::render('properties/show', [
            'property' => $property,
            'averageRating' => $averageRating ? round($averageRating, 1) : null,
            'totalReviews' => $totalReviews,
            'unavailableDates' => $unavailableDates,
        ]);
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        $user = auth()->user();
        
        if (!$user->isAccommodationOwner() && !$user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        if ($user->isAccommodationOwner() && $property->accommodation_owner_id !== $user->accommodationOwner->id) {
            abort(403, 'Unauthorized.');
        }

        return Inertia::render('properties/edit', [
            'property' => $property
        ]);
    }

    /**
     * Update the specified property.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $user = $request->user();
        
        if (!$user->isAccommodationOwner() && !$user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        if ($user->isAccommodationOwner() && $property->accommodation_owner_id !== $user->accommodationOwner->id) {
            abort(403, 'Unauthorized.');
        }

        $property->update($request->validated());

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified property.
     */
    public function destroy(Property $property)
    {
        $user = auth()->user();
        
        if (!$user->isAccommodationOwner() && !$user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        if ($user->isAccommodationOwner() && $property->accommodation_owner_id !== $user->accommodationOwner->id) {
            abort(403, 'Unauthorized.');
        }

        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Property deleted successfully.');
    }
}