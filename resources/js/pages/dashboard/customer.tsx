import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

interface Booking {
    id: number;
    property: {
        id: number;
        name: string;
        city: string;
        state: string;
        accommodation_owner: {
            user: {
                name: string;
            };
        };
    };
    check_in_date: string;
    check_out_date: string;
    guests: number;
    total_price: number;
    status: string;
    review?: {
        id: number;
        rating: number;
    };
}

interface Props {
    stats: {
        totalBookings: number;
        upcomingTrips: number;
        unreadMessages: number;
    };
    currentBooking?: Booking;
    upcomingBookings: Booking[];
    pastBookings: Booking[];
    [key: string]: unknown;
}

export default function CustomerDashboard({ stats, currentBooking, upcomingBookings, pastBookings }: Props) {
    const getStatusColor = (status: string) => {
        switch (status) {
            case 'confirmed': return 'bg-green-100 text-green-800';
            case 'checked_in': return 'bg-blue-100 text-blue-800';
            case 'checked_out': return 'bg-gray-100 text-gray-800';
            case 'cancelled': return 'bg-red-100 text-red-800';
            default: return 'bg-yellow-100 text-yellow-800';
        }
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };

    const renderStars = (rating: number) => {
        return '⭐'.repeat(rating);
    };

    return (
        <AppShell>
            <Head title="My Dashboard" />
            
            <div className="container mx-auto px-4 py-8">
                {/* Header */}
                <div className="flex justify-between items-center mb-8">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">🧳 My Travel Dashboard</h1>
                        <p className="text-gray-600 mt-2">Manage your bookings and discover new places</p>
                    </div>
                    <Link href="/properties">
                        <Button size="lg">
                            🔍 Find Accommodations
                        </Button>
                    </Link>
                </div>

                {/* Stats */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <Card>
                        <CardHeader className="pb-2">
                            <CardDescription>Total Bookings</CardDescription>
                            <CardTitle className="text-3xl">📈 {stats.totalBookings}</CardTitle>
                        </CardHeader>
                    </Card>
                    
                    <Card>
                        <CardHeader className="pb-2">
                            <CardDescription>Upcoming Trips</CardDescription>
                            <CardTitle className="text-3xl">✈️ {stats.upcomingTrips}</CardTitle>
                        </CardHeader>
                    </Card>
                    
                    <Card>
                        <CardHeader className="pb-2">
                            <CardDescription>Unread Messages</CardDescription>
                            <CardTitle className="text-3xl">💬 {stats.unreadMessages}</CardTitle>
                        </CardHeader>
                    </Card>
                </div>

                {/* Current Booking */}
                {currentBooking && (
                    <Card className="mb-8 border-blue-200 bg-blue-50">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                🏨 Currently Staying
                                <Badge className={getStatusColor(currentBooking.status)}>
                                    {currentBooking.status.replace('_', ' ')}
                                </Badge>
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="grid md:grid-cols-2 gap-4">
                                <div>
                                    <h3 className="font-semibold text-lg mb-2">{currentBooking.property.name}</h3>
                                    <p className="text-gray-600 mb-1">
                                        📍 {currentBooking.property.city}, {currentBooking.property.state}
                                    </p>
                                    <p className="text-gray-600 mb-1">
                                        🏠 Host: {currentBooking.property.accommodation_owner.user.name}
                                    </p>
                                    <p className="text-gray-600">
                                        👥 {currentBooking.guests} guests
                                    </p>
                                </div>
                                <div className="flex flex-col justify-between">
                                    <div>
                                        <p className="text-sm text-gray-600">Check-out</p>
                                        <p className="font-semibold">{formatDate(currentBooking.check_out_date)}</p>
                                    </div>
                                    <div className="flex gap-2 mt-4">
                                        <Link href={`/bookings/${currentBooking.id}`}>
                                            <Button variant="outline" size="sm">
                                                👁️ View Details
                                            </Button>
                                        </Link>
                                        <Button size="sm">
                                            💬 Contact Host
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                )}

                {/* Upcoming Bookings */}
                {upcomingBookings.length > 0 && (
                    <Card className="mb-8">
                        <CardHeader>
                            <CardTitle>🗓️ Upcoming Trips</CardTitle>
                            <CardDescription>Your confirmed bookings</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-4">
                                {upcomingBookings.map((booking) => (
                                    <div key={booking.id} className="border rounded-lg p-4">
                                        <div className="flex justify-between items-start">
                                            <div>
                                                <h4 className="font-semibold">{booking.property.name}</h4>
                                                <p className="text-sm text-gray-600">
                                                    📍 {booking.property.city}, {booking.property.state}
                                                </p>
                                                <p className="text-sm text-gray-600">
                                                    📅 {formatDate(booking.check_in_date)} - {formatDate(booking.check_out_date)}
                                                </p>
                                                <p className="text-sm text-gray-600">
                                                    👥 {booking.guests} guests • 💰 ${booking.total_price}
                                                </p>
                                            </div>
                                            <div className="flex flex-col items-end gap-2">
                                                <Badge className={getStatusColor(booking.status)}>
                                                    {booking.status.replace('_', ' ')}
                                                </Badge>
                                                <Link href={`/bookings/${booking.id}`}>
                                                    <Button variant="outline" size="sm">
                                                        👁️ Details
                                                    </Button>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>
                )}

                {/* Past Bookings */}
                {pastBookings.length > 0 && (
                    <Card>
                        <CardHeader>
                            <CardTitle>📚 Recent Stays</CardTitle>
                            <CardDescription>Your travel history</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-4">
                                {pastBookings.map((booking) => (
                                    <div key={booking.id} className="border rounded-lg p-4">
                                        <div className="flex justify-between items-start">
                                            <div>
                                                <h4 className="font-semibold">{booking.property.name}</h4>
                                                <p className="text-sm text-gray-600">
                                                    📍 {booking.property.city}, {booking.property.state}
                                                </p>
                                                <p className="text-sm text-gray-600">
                                                    📅 {formatDate(booking.check_in_date)} - {formatDate(booking.check_out_date)}
                                                </p>
                                                <p className="text-sm text-gray-600">
                                                    👥 {booking.guests} guests • 💰 ${booking.total_price}
                                                </p>
                                                {booking.review && (
                                                    <p className="text-sm text-green-600 mt-1">
                                                        ⭐ Your review: {renderStars(booking.review.rating)}
                                                    </p>
                                                )}
                                            </div>
                                            <div className="flex flex-col items-end gap-2">
                                                <Badge className={getStatusColor(booking.status)}>
                                                    {booking.status.replace('_', ' ')}
                                                </Badge>
                                                <div className="flex gap-1">
                                                    <Link href={`/bookings/${booking.id}`}>
                                                        <Button variant="outline" size="sm">
                                                            👁️ Details
                                                        </Button>
                                                    </Link>
                                                    {!booking.review && (
                                                        <Button size="sm">
                                                            ⭐ Review
                                                        </Button>
                                                    )}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>
                )}

                {/* Empty State */}
                {upcomingBookings.length === 0 && pastBookings.length === 0 && !currentBooking && (
                    <Card className="text-center py-12">
                        <CardContent>
                            <div className="text-6xl mb-4">🧳</div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">
                                No bookings yet
                            </h3>
                            <p className="text-gray-600 mb-6">
                                Start your journey by exploring amazing accommodations
                            </p>
                            <Link href="/properties">
                                <Button size="lg">
                                    🔍 Browse Properties
                                </Button>
                            </Link>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AppShell>
    );
}