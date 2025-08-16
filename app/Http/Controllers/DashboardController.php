<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\Review;
use App\Models\SecurityLog;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'accommodation_owner':
                return $this->ownerDashboard($user);
            case 'customer':
                return $this->customerDashboard($user);
            case 'cleaning_staff':
                return $this->cleaningDashboard($user);
            case 'security_staff':
                return $this->securityDashboard($user);
            default:
                return Inertia::render('dashboard');
        }
    }

    /**
     * Admin dashboard with system overview.
     */
    protected function adminDashboard()
    {
        $totalProperties = Property::count();
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('status', '!=', 'cancelled')->sum('total_price');
        $activeUsers = \App\Models\User::where('is_active', true)->count();

        // Recent activity
        $recentBookings = Booking::with(['property', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        $recentReviews = Review::with(['property', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        // Monthly booking trends
        $bookingTrends = Booking::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_price) as revenue')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', '!=', 'cancelled')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('dashboard/admin', [
            'stats' => [
                'totalProperties' => $totalProperties,
                'totalBookings' => $totalBookings,
                'totalRevenue' => $totalRevenue,
                'activeUsers' => $activeUsers,
            ],
            'recentBookings' => $recentBookings,
            'recentReviews' => $recentReviews,
            'bookingTrends' => $bookingTrends,
        ]);
    }

    /**
     * Accommodation owner dashboard with property management overview.
     */
    protected function ownerDashboard($user)
    {
        $accommodationOwner = $user->accommodationOwner;
        
        if (!$accommodationOwner) {
            return Inertia::render('dashboard/setup-owner');
        }

        $properties = Property::where('accommodation_owner_id', $accommodationOwner->id)->get();
        $propertyIds = $properties->pluck('id');

        $totalBookings = Booking::whereIn('property_id', $propertyIds)->count();
        $activeBookings = Booking::whereIn('property_id', $propertyIds)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->count();
        $totalRevenue = Booking::whereIn('property_id', $propertyIds)
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');
        
        $pendingServiceRequests = ServiceRequest::whereHas('booking', function ($query) use ($propertyIds) {
            $query->whereIn('property_id', $propertyIds);
        })->where('status', 'open')->count();

        // Recent bookings
        $recentBookings = Booking::with(['property', 'user'])
            ->whereIn('property_id', $propertyIds)
            ->latest()
            ->limit(10)
            ->get();

        // Upcoming check-ins
        $upcomingCheckIns = Booking::with(['property', 'user'])
            ->whereIn('property_id', $propertyIds)
            ->where('status', 'confirmed')
            ->where('check_in_date', '>=', Carbon::today())
            ->where('check_in_date', '<=', Carbon::today()->addDays(7))
            ->orderBy('check_in_date')
            ->get();

        // Service requests
        $serviceRequests = ServiceRequest::with(['booking.property', 'booking.user', 'assignedTo'])
            ->whereHas('booking', function ($query) use ($propertyIds) {
                $query->whereIn('property_id', $propertyIds);
            })
            ->where('status', '!=', 'completed')
            ->latest()
            ->limit(10)
            ->get();

        // Unread messages
        $unreadMessages = Message::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->count();

        return Inertia::render('dashboard/owner', [
            'stats' => [
                'totalProperties' => $properties->count(),
                'totalBookings' => $totalBookings,
                'activeBookings' => $activeBookings,
                'totalRevenue' => $totalRevenue,
                'pendingServiceRequests' => $pendingServiceRequests,
                'unreadMessages' => $unreadMessages,
            ],
            'properties' => $properties,
            'recentBookings' => $recentBookings,
            'upcomingCheckIns' => $upcomingCheckIns,
            'serviceRequests' => $serviceRequests,
        ]);
    }

    /**
     * Customer dashboard with booking history and upcoming trips.
     */
    protected function customerDashboard($user)
    {
        $totalBookings = Booking::where('user_id', $user->id)->count();
        $upcomingTrips = Booking::where('user_id', $user->id)
            ->where('check_in_date', '>=', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->count();
        
        // Current booking
        $currentBooking = Booking::with(['property.accommodationOwner.user'])
            ->where('user_id', $user->id)
            ->where('status', 'checked_in')
            ->first();

        // Upcoming bookings
        $upcomingBookings = Booking::with(['property.accommodationOwner.user'])
            ->where('user_id', $user->id)
            ->where('check_in_date', '>=', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('check_in_date')
            ->get();

        // Past bookings
        $pastBookings = Booking::with(['property.accommodationOwner.user', 'review'])
            ->where('user_id', $user->id)
            ->where('check_out_date', '<', Carbon::today())
            ->orderBy('check_out_date', 'desc')
            ->limit(5)
            ->get();

        // Unread messages
        $unreadMessages = Message::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->count();

        return Inertia::render('dashboard/customer', [
            'stats' => [
                'totalBookings' => $totalBookings,
                'upcomingTrips' => $upcomingTrips,
                'unreadMessages' => $unreadMessages,
            ],
            'currentBooking' => $currentBooking,
            'upcomingBookings' => $upcomingBookings,
            'pastBookings' => $pastBookings,
        ]);
    }

    /**
     * Cleaning staff dashboard with assigned tasks.
     */
    protected function cleaningDashboard($user)
    {
        $assignedTasks = ServiceRequest::with(['booking.property', 'booking.user'])
            ->where('assigned_to', $user->id)
            ->where('type', 'cleaning')
            ->where('status', '!=', 'completed')
            ->orderBy('priority')
            ->orderBy('created_at')
            ->get();

        $completedTasks = ServiceRequest::where('assigned_to', $user->id)
            ->where('type', 'cleaning')
            ->where('status', 'completed')
            ->count();

        $pendingTasks = $assignedTasks->where('status', 'open')->count();
        $inProgressTasks = $assignedTasks->where('status', 'in_progress')->count();

        return Inertia::render('dashboard/cleaning', [
            'stats' => [
                'pendingTasks' => $pendingTasks,
                'inProgressTasks' => $inProgressTasks,
                'completedTasks' => $completedTasks,
            ],
            'assignedTasks' => $assignedTasks,
        ]);
    }

    /**
     * Security staff dashboard with security logs and alerts.
     */
    protected function securityDashboard($user)
    {
        $unresolvedLogs = SecurityLog::with(['property'])
            ->where('is_resolved', false)
            ->orderBy('severity')
            ->orderBy('created_at')
            ->get();

        $criticalAlerts = $unresolvedLogs->where('severity', 'critical')->count();
        $highAlerts = $unresolvedLogs->where('severity', 'high')->count();
        $totalLogs = SecurityLog::where('logged_by', $user->id)->count();

        // Recent security events
        $recentLogs = SecurityLog::with(['property', 'loggedBy'])
            ->latest()
            ->limit(10)
            ->get();

        return Inertia::render('dashboard/security', [
            'stats' => [
                'criticalAlerts' => $criticalAlerts,
                'highAlerts' => $highAlerts,
                'totalLogs' => $totalLogs,
            ],
            'unresolvedLogs' => $unresolvedLogs,
            'recentLogs' => $recentLogs,
        ]);
    }
}