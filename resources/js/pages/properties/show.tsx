import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Property {
    id: number;
    name: string;
    description: string;
    type: string;
    address: string;
    city: string;
    state: string;
    country: string;
    max_guests: number;
    bedrooms: number;
    bathrooms: number;
    price_per_night: number;
    amenities: string[] | null;
    photos: string[] | null;
    accommodation_owner: {
        business_name: string;
        user: {
            name: string;
        };
    };
    reviews: Array<{
        id: number;
        rating: number;
        comment: string;
        user: {
            name: string;
        };
        created_at: string;
    }>;
}

interface Props {
    property: Property;
    averageRating?: number;
    totalReviews: number;
    unavailableDates?: Array<{
        start: string;
        end: string;
    }>;
    auth?: {
        user?: {
            id: number;
            role: string;
        };
    };
    [key: string]: unknown;
}

export default function PropertyShow({ property, averageRating, totalReviews, auth }: Props) {
    const [bookingForm, setBookingForm] = useState({
        check_in_date: '',
        check_out_date: '',
        guests: 1,
        special_requests: '',
    });

    const handleBooking = (e: React.FormEvent) => {
        e.preventDefault();
        if (!auth?.user) {
            router.visit('/login');
            return;
        }

        router.post('/bookings', {
            property_id: property.id,
            ...bookingForm,
        });
    };

    const renderStars = (rating: number) => {
        return '⭐'.repeat(Math.round(rating));
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };

    return (
        <AppShell>
            <Head title={property.name} />
            
            <div className="container mx-auto px-4 py-8">
                {/* Back Button */}
                <div className="mb-6">
                    <Link href="/properties">
                        <Button variant="outline">
                            ← Back to Properties
                        </Button>
                    </Link>
                </div>

                {/* Property Header */}
                <div className="grid lg:grid-cols-3 gap-8">
                    <div className="lg:col-span-2">
                        {/* Photo Placeholder */}
                        <div className="aspect-video bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg mb-6 flex items-center justify-center">
                            <span className="text-6xl">🏠</span>
                        </div>

                        {/* Property Info */}
                        <div className="mb-8">
                            <div className="flex items-center gap-2 mb-4">
                                <h1 className="text-3xl font-bold text-gray-900">{property.name}</h1>
                                <Badge variant="secondary">{property.type}</Badge>
                            </div>
                            
                            <div className="flex items-center gap-4 text-gray-600 mb-4">
                                <span>📍 {property.city}, {property.state}, {property.country}</span>
                                {averageRating && (
                                    <span className="flex items-center gap-1">
                                        {renderStars(averageRating)} 
                                        <span className="ml-1">({totalReviews} reviews)</span>
                                    </span>
                                )}
                            </div>

                            <div className="grid grid-cols-3 gap-4 mb-6">
                                <div className="text-center p-4 bg-gray-50 rounded-lg">
                                    <div className="text-2xl mb-1">👥</div>
                                    <div className="font-semibold">{property.max_guests}</div>
                                    <div className="text-sm text-gray-600">guests</div>
                                </div>
                                <div className="text-center p-4 bg-gray-50 rounded-lg">
                                    <div className="text-2xl mb-1">🛏️</div>
                                    <div className="font-semibold">{property.bedrooms}</div>
                                    <div className="text-sm text-gray-600">bedrooms</div>
                                </div>
                                <div className="text-center p-4 bg-gray-50 rounded-lg">
                                    <div className="text-2xl mb-1">🚿</div>
                                    <div className="font-semibold">{property.bathrooms}</div>
                                    <div className="text-sm text-gray-600">bathrooms</div>
                                </div>
                            </div>

                            <div className="mb-6">
                                <h2 className="text-xl font-semibold mb-3">📋 Description</h2>
                                <p className="text-gray-700 leading-relaxed">{property.description}</p>
                            </div>

                            {property.amenities && property.amenities.length > 0 && (
                                <div className="mb-6">
                                    <h2 className="text-xl font-semibold mb-3">✨ Amenities</h2>
                                    <div className="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        {property.amenities.map((amenity, index) => (
                                            <Badge key={index} variant="outline" className="justify-start">
                                                {amenity}
                                            </Badge>
                                        ))}
                                    </div>
                                </div>
                            )}

                            <div className="mb-6">
                                <h2 className="text-xl font-semibold mb-3">🏠 Hosted by</h2>
                                <div className="flex items-center gap-3">
                                    <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span className="text-lg">👤</span>
                                    </div>
                                    <div>
                                        <p className="font-semibold">{property.accommodation_owner.user.name}</p>
                                        <p className="text-gray-600">{property.accommodation_owner.business_name}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Booking Card */}
                    <div className="lg:col-span-1">
                        <Card className="sticky top-8">
                            <CardHeader>
                                <CardTitle className="text-2xl">
                                    ${property.price_per_night}
                                    <span className="text-base font-normal text-gray-600">/night</span>
                                </CardTitle>
                                {averageRating && (
                                    <CardDescription>
                                        {renderStars(averageRating)} {totalReviews} reviews
                                    </CardDescription>
                                )}
                            </CardHeader>
                            <CardContent>
                                {auth?.user?.role === 'customer' ? (
                                    <form onSubmit={handleBooking} className="space-y-4">
                                        <div className="grid grid-cols-2 gap-2">
                                            <div>
                                                <Label htmlFor="check_in_date">Check-in</Label>
                                                <Input
                                                    id="check_in_date"
                                                    type="date"
                                                    required
                                                    value={bookingForm.check_in_date}
                                                    onChange={(e) => setBookingForm({ 
                                                        ...bookingForm, 
                                                        check_in_date: e.target.value 
                                                    })}
                                                />
                                            </div>
                                            <div>
                                                <Label htmlFor="check_out_date">Check-out</Label>
                                                <Input
                                                    id="check_out_date"
                                                    type="date"
                                                    required
                                                    value={bookingForm.check_out_date}
                                                    onChange={(e) => setBookingForm({ 
                                                        ...bookingForm, 
                                                        check_out_date: e.target.value 
                                                    })}
                                                />
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <Label htmlFor="guests">Guests</Label>
                                            <Input
                                                id="guests"
                                                type="number"
                                                min="1"
                                                max={property.max_guests}
                                                required
                                                value={bookingForm.guests}
                                                onChange={(e) => setBookingForm({ 
                                                    ...bookingForm, 
                                                    guests: parseInt(e.target.value) 
                                                })}
                                            />
                                        </div>

                                        <Button type="submit" className="w-full" size="lg">
                                            📅 Book Now
                                        </Button>
                                    </form>
                                ) : (
                                    <div className="text-center py-4">
                                        {!auth?.user ? (
                                            <div>
                                                <p className="text-gray-600 mb-4">Sign in to book this property</p>
                                                <Link href="/login">
                                                    <Button className="w-full">
                                                        🔑 Sign In
                                                    </Button>
                                                </Link>
                                            </div>
                                        ) : (
                                            <div>
                                                <p className="text-gray-600 mb-4">
                                                    Only customers can book properties
                                                </p>
                                                <Button disabled className="w-full">
                                                    📅 Booking Not Available
                                                </Button>
                                            </div>
                                        )}
                                    </div>
                                )}

                                <div className="mt-4 text-center text-sm text-gray-600">
                                    <p>📞 Questions? Contact the host</p>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>

                {/* Reviews Section */}
                {property.reviews.length > 0 && (
                    <div className="mt-12">
                        <h2 className="text-2xl font-bold mb-6">
                            ⭐ Reviews ({totalReviews})
                        </h2>
                        <div className="grid md:grid-cols-2 gap-6">
                            {property.reviews.slice(0, 6).map((review) => (
                                <Card key={review.id}>
                                    <CardHeader className="pb-3">
                                        <div className="flex justify-between items-start">
                                            <div>
                                                <CardTitle className="text-base">{review.user.name}</CardTitle>
                                                <CardDescription>
                                                    {formatDate(review.created_at)}
                                                </CardDescription>
                                            </div>
                                            <div className="text-sm">
                                                {renderStars(review.rating)}
                                            </div>
                                        </div>
                                    </CardHeader>
                                    {review.comment && (
                                        <CardContent>
                                            <p className="text-gray-700">{review.comment}</p>
                                        </CardContent>
                                    )}
                                </Card>
                            ))}
                        </div>
                    </div>
                )}

                {/* Location */}
                <div className="mt-12">
                    <h2 className="text-2xl font-bold mb-6">📍 Location</h2>
                    <Card>
                        <CardContent className="pt-6">
                            <div className="aspect-video bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                                <div className="text-center">
                                    <div className="text-4xl mb-2">🗺️</div>
                                    <p className="text-gray-600">Map placeholder</p>
                                </div>
                            </div>
                            <p className="text-gray-700">
                                <strong>Address:</strong> {property.address}<br />
                                {property.city}, {property.state}, {property.country}
                            </p>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppShell>
    );
}